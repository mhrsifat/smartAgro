<?php

namespace Modules\Donation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'currency',
        'message',
        'anonymous',
        'payment_gateway',
        'transaction_id',
        'status',
    ];
}