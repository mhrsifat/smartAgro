<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'user_id',
        'user_key',
        'status',
        'file_path',
        'excerpt',
        'html_length',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}