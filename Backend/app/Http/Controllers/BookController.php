<?php

namespace App\Http\Controllers;

use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    protected $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function store(StoreBookRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        $book = $this->bookRepository->create($data);
        return response()->json(["book" => $book], 201);
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $book = $this->bookRepository->findById($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found']);
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();

        $updatedBook = $this->bookRepository->update($book, $data);
        return response()->json(['book' => $updatedBook], 200);
    }

    public function index()
    {
        $books = $this->bookRepository->all();
        if ($books->isEmpty()) {
            return response()->json(["message" => "There are no books"], 200);
        }

        return response()->json(["books" => $books], 200);
    }

    public function show($slug)
    {
        $book = $this->bookRepository->findBySlug($slug);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        return response()->json(["book" => $book], 200);
    }

    public function destroy($id)
    {
        $book = $this->bookRepository->findById($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $this->bookRepository->delete($book);
        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}
