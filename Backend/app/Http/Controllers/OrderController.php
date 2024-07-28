<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->with('books')->first();

        if (!$cart || $cart->books->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        $totalPrice = 0;
        foreach ($cart->books as $book) {
            $totalPrice += $book->price * $book->pivot->quantity;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        foreach ($cart->books as $book) {
            $order->books()->attach($book->id, ['quantity' => $book->pivot->quantity]);
        }

        $cart->books()->detach();
        return response()->json(['order' => $order], 201);
    }
}
