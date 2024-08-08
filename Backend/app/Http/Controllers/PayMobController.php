<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PayMobController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function pay(Request $request)
    {
        $userId = $request->user()->id;
        $cart = Cart::where('user_id', $userId)->firstOrFail();
        $totalAmount = $cart->total_amount;

        $headers = [
            'Authorization' => 'Token ' . env('PAYMOB_API_SECRET'),
            'Content-Type' => 'application/json'
        ];

        $data = [
            'amount' => $totalAmount * 100,
            'currency' => "EGP",
            'payment_methods' => [4619062],
            'billing_data' => [
                "apartment" => "2",
                "first_name" => $request->contact_name,
                "last_name" => $request->contact_name,
                "street" => "938, Al-Jadeed Bldg",
                "building" => "11",
                "phone_number" => $request->contact_number,
                "country" => "OMN",
                "email" => "dohaseif@gmail.com",
                "floor" => "1",
                "state" => "Mansoura"
            ]
        ];

        $response = $this->client->post('https://accept.paymob.com/v1/intention/', [
            'headers' => $headers,
            'json' => $data,
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        $secret_key = $responseData['client_secret'];
        $url_billing = 'https://accept.paymob.com/unifiedcheckout/?publicKey=' . env('PAYMOB_PUBLIC_KEY') . '&clientSecret=' . $secret_key;

        return response()->json([
            'client_secret' => $secret_key,
            'url_billing' => $url_billing
        ]);
    }
}
