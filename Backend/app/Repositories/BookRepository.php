<?php

namespace App\Repositories;

use App\Repositories\Contracts\BookRepositoryInterface;
use App\Models\Book;

class BookRepository implements BookRepositoryInterface
{
    public function all()
    {
        return Book::all();
    }

    public function findById($id)
    {
        return Book::find($id);
    }

    public function findBySlug($slug)
    {
        return Book::where('slug', $slug)->first();
    }

    public function create(array $data)
    {
        return Book::create($data);
    }

    public function update(Book $book, array $data)
    {
        $book->update($data);
        return $book;
    }

    public function delete(Book $book)
    {
        return $book->delete();
    }
}
