<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'page_num',
        'author',
        'user_id',
        'type_id',
        'price',
        'isbn',
        'publisher',
        'quantity',
    ];
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images()
    {
        return $this->hasMany(BookImage::class);
    }
}
