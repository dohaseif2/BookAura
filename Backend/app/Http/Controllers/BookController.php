<?php

namespace App\Http\Controllers;

use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
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
        return response()->json(["book" => $book], 201);
    }
    public function update(UpdateBookRequest $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found']);
        }
        $data = $request->all();
        $data['user_id'] = Auth::id();

        $book->update($data);

        return response()->json(['book' => $book], 200);
    }
    public function index()
    {
        $books = Book::all();
        if ($books->isEmpty()) {
            return response()->json(["message" => "There is no books"], 201);
        }
        return response()->json(["books" => $books], 201);
    }
    public function show($slug)
    {
        $book = Book::where('slug', $slug)->get();
        if (!$book) {
            return response()->json(['error' => 'Book not found']);
        }
        return response()->json(["book" => $book], 201);
    }
    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found']);
        }
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }
}
