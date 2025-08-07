<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->slug = Str::slug($request->name);
        $product->category_name = $request->category_name;
        

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('products', 'public');
            $product->image_url = config('app.url') . '/storage/' . $imagePath;
        }
        
        $product->save();

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->slug = Str::slug($request->name);
        $product->category_name = $request->category_name;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('products', 'public');
            $product->image_url = config('app.url') . '/storage/' . $imagePath;
        }

        $product->update();

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image_url) {
            Storage::delete($product->image_url);
        }
        $product->delete();
        return response()->noContent();
    }
}
