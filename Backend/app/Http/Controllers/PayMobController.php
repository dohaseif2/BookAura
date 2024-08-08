<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Services\PayMobService;

class PayMobController extends Controller
{
    protected $payMobService;

    public function __construct(PayMobService $payMobService)
    {
        $this->payMobService = $payMobService;
    }

    public function pay(Request $request)
    {
        $userId = $request->user()->id;
        $cart = Cart::where('user_id', $userId)->firstOrFail();
        $totalAmount = $cart->total_amount;

        $paymentResponse = $this->payMobService->createPaymentIntent(
            $totalAmount,
            $request->contact_name,
            $request->contact_number,
        );
        return $paymentResponse;
    }

    public function callback(Request $request)
    {
        $data = $request->all();

        if ($this->payMobService->verifyCallback($data)) {
            $status = $data['success'];

            if ($status === "true") {
                return response()->json(['message'=>'success']);
            } else {
                return response()->json(['message'=>'Something Went Wrong Please Try Again']);
            }
        } else {
            return 'error';
        }
    }
}
