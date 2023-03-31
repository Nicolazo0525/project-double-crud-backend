<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'author',
        'editorial',
        'edition',
        'pages',
        'status',
        'categories_id',
    ];
}
