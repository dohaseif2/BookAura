<?php

namespace App\Repositories\Contracts;

use App\Models\Cart;

interface CartRepositoryInterface
{
    public function getUserCart($userId);

    public function addBookToCart(Cart $cart, $bookId, $quantity);

    public function removeBookFromCart(Cart $cart, $bookId);
}
