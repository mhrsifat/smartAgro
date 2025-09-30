<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name', 
        'email', 
        'subject', 
        'message', 
        'replied', 
        'replied_by', 
        'replied_at'
    ];

    public function replier() {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
