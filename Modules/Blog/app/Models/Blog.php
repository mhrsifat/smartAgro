<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Blog\Database\Factories\BlogFactory;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'slug',
        'image',
        'user_id',
    ];

    // protected static function newFactory(): BlogFactory
    // {
    //     // return BlogFactory::new();
    // }
    
    public function getRouteKeyName()
{
    return 'slug';
}
}
