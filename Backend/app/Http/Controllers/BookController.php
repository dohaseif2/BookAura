<?php

namespace App\Http\Controllers;

use App\Http\Requests\Books\StoreBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function store(StoreBookRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        $book = Book::create($data);
        return response()->json($book, 201);
    }
}