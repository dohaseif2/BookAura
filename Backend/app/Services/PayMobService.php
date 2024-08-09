<?php

namespace App\Services;

use GuzzleHttp\Client;

class PayMobService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }
    public function createPaymentIntent($totalAmount, $contactName, $contactNumber)
    {
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
                "first_name" => $contactName,
                "last_name" => $contactName,
                "street" => "938, Al-Jadeed Bldg",
                "building" => "11",
                "phone_number" => $contactNumber,
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
    
        return [
            'client_secret' => $secret_key,
            'url_billing' => $url_billing
        ];
    }
    public function verifyCallback($data)
    {
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
        return $hased === $hmac;
    }
}
