<?php

namespace App\Repositories\Contracts;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function all();

    public function findById($id);

    public function findBySlug($slug);

    public function create(array $data);

    public function update(Book $book, array $data);

    public function delete(Book $book);
}
