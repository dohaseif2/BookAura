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
    public function callback(Request $request)
    {
        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ($hased === $hmac) {
            $status = $data['success'];

            if ($status === "true") {
                return response()->json(['message' => 'success']);
            } else {
                return response()->json(['message' => 'Something Went Wrong Please Try Again']);
            }
        } else {
            return response()->json(['message' => 'error']);
        }
    }
}
