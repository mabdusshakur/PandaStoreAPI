<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Order;
use App\Models\Product;
use App\Services\SSLCommerz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = auth()->user()->orders()->with('product')->get();
        return OrderResource::collection($orders);
    }

    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($request->input('product_id'));
        $total_price = $product->price * $request->input('quantity');

        $order = Order::create([
            'product_id'=> $request->input('product_id'),
            'user_id'=> $request->user()->id,
            'quantity'=> $request->input('quantity'),
            'total_price'=> $total_price,
            'delivery_status' => 'pending',
            'payment_status' => 'pending'
        ]);

        $post_data = array();
        $post_data['store_id'] = config('sslcommerz.store_id');
        $post_data['store_passwd'] = config('sslcommerz.store_password');
        $post_data['total_amount'] = $order->total_price;
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $order->id;
        $post_data['success_url'] = route('payment.success');
        $post_data['fail_url'] = route('payment.fail');
        $post_data['cancel_url'] = route('payment.cancel');
        $post_data['cus_name'] = auth()->user()->name;
        $post_data['cus_email'] = auth()->user()->email;

        $sslc = new SSLCommerz($post_data);
        $response = $sslc->makePayment();

        return response()->json([
            'message' => 'Order created successfully',
            'order' => new OrderResource($order->load('product')),
            'payment_url' => $response,
        ]);
    }

    public function success(Request $request)
    {
        // Handle successful payment logic here
        $order = Order::where('id', $request->input('tran_id'))->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update(['payment_status' => 'completed']);
        return new OrderResource($order);
    }

    public function fail(Request $request)
    {
        $order = Order::where('id', $request->input('tran_id'))->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update(['payment_status' => 'failed']);
        // Handle failed payment logic here
        return response()->json(['message' => 'Payment failed'], 400);
    }

    public function cancel(Request $request)
    {
        $order = Order::where('id', $request->input('tran_id'))->first();
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update(['payment_status' => 'cancelled']);
        // Handle cancelled payment logic here
        return response()->json(['message' => 'Payment cancelled'], 400);
    }
}
