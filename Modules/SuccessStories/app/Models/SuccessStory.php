<?php

namespace Modules\SuccessStories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\SuccessStories\Database\Factories\SuccessStoryFactory;

class SuccessStory extends Model
{
    use HasFactory;
    protected $table = 'success_stories';

    protected $fillable = [
        'title',
        'summary',
        'content',
        'image',
        'author',
        'slug',
        'status'
    ];
}
