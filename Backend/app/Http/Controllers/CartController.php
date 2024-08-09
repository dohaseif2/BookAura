<?php

namespace App\Http\Controllers;

use App\Http\Requests\Carts\StoreCartRequest;
use App\Models\Book;
use App\Models\Cart;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index()
    {
        $cart = $this->cartRepository->getUserCart(Auth::id());

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        $books = $cart->books()->get();

        return response()->json(['cart' => $books]);
    }

    public function add(StoreCartRequest $request, $bookId)
    {
        $cart = $this->cartRepository->getUserCart(Auth::id()) ?? Cart::create(['user_id' => Auth::id()]);

        $book = Book::find($bookId);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $this->cartRepository->addBookToCart($cart, $bookId, $request->quantity);

        return response()->json(['message' => 'Book added to cart'], 201);
    }

    public function remove($bookId)
    {
        $cart = $this->cartRepository->getUserCart(Auth::id());

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        $bookInCart = $cart->books()->where('book_id', $bookId)->exists();

        if (!$bookInCart) {
            return response()->json(['message' => 'Book not found in cart'], 404);
        }

        $this->cartRepository->removeBookFromCart($cart, $bookId);

        return response()->json(['message' => 'Book removed from cart']);
    }
}
