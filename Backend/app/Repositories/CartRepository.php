<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function getUserCart($userId)
    {
        return Cart::where('user_id', $userId)->first();
    }

    public function addBookToCart(Cart $cart, $bookId, $quantity)
    {
        $existingBook = $cart->books()->where('book_id', $bookId)->first();

        if ($existingBook) {
            $cart->books()->updateExistingPivot($bookId, ['quantity' => $existingBook->pivot->quantity + $quantity]);
        } else {
            $cart->books()->attach($bookId, ['quantity' => $quantity]);
        }
    }

    public function removeBookFromCart(Cart $cart, $bookId)
    {
        $cart->books()->detach($bookId);
    }
}
