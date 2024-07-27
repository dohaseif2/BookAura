<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            $book->slug = static::generateSlug($book->title);
        });

        static::updating(function ($book) {
            if ($book->isDirty('title')) {
                $book->slug = static::generateSlug($book->title);
            }
        });
    }

    public static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

   
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
