<?php

namespace App\Http\Controllers;

use App\Http\Requests\Carts\StoreCartRequest;
use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(StoreCartRequest $request, $bookId)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $book = Book::find($bookId);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $existingBook = $cart->books()->where('book_id', $bookId)->first();

        if ($existingBook) {
            $cart->books()->updateExistingPivot($bookId, ['quantity' => $existingBook->pivot->quantity + $request->quantity]);
        } else {
            $cart->books()->attach($bookId, ['quantity' => $request->quantity]);
        }

        return response()->json(['message' => 'Book added to cart'], 201);
    }
}
