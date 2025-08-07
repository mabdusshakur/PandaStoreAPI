<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SSLCommerz
{
    protected $data;
    protected $sandbox;
    protected $api_url;
    protected $validation_url;

    public function __construct($data)
    {
        $this->data = $data;
        $this->sandbox = config('sslcommerz.sandbox_mode', true);
        $this->api_url = $this->sandbox? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php' : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
    }

    public function makePayment()
    {
        $postData = [
            'store_id' => $this->data['store_id'],
            'store_passwd' => $this->data['store_passwd'],
            'total_amount' => $this->data['total_amount'],
            'currency' => $this->data['currency'],
            'tran_id' => $this->data['tran_id'],
            'success_url' => $this->data['success_url'],
            'fail_url' => $this->data['fail_url'],
            'cancel_url' => $this->data['cancel_url'],
            'product_category' => 'general',
            'emi_option' => 0,
            'cus_name' => $this->data['cus_name'] ?? 'Customer Name',
            'cus_email' => $this->data['cus_email'] ?? 'customer@example.com',
            'cus_phone' => '0170000000',
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',
            'product_profile' => 'physical-goods',
            'shipping_method' => 'NO',
            'product_name' => 'Products from ' . config('app.name')
        ];

        $response = Http::asForm()->post($this->api_url, $postData);
        $responseData = $response->json();

        return $responseData;
    }
}