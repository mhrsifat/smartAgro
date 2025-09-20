<?php

namespace Modules\Research\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Research\Database\Factories\ResearchFactory;

class Research extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'slug',
        'image',
        'authors',
        'status',
        'is_featured',
        'download_url',
        'user_id',
    ];

    // protected static function newFactory(): ResearchFactory
    // {
    //     // return ResearchFactory::new();
    // }
    
    public function getRouteKeyName()
{
    return 'slug';
}
}
