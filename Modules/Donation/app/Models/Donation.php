<?php

namespace Modules\Donation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Donation extends Model
{
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
        'admin_notes',
        'status_updated_at',
        'status_updated_by',
        'donated_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'anonymous' => 'boolean',
        'donated_at' => 'datetime',
        'status_updated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who made this donation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who last updated the status
     */
    public function statusUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    /**
     * Scope for completed donations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending donations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed donations
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for cancelled donations
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for donations within date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for donations by payment gateway
     */
    public function scopeByPaymentGateway($query, $gateway)
    {
        return $query->where('payment_gateway', $gateway);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    /**
     * Get status badge color class
     */
    public function getStatusColorClassAttribute()
    {
        return match($this->status) {
            'completed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get payment gateway badge color class
     */
    public function getPaymentGatewayColorClassAttribute()
    {
        return match($this->payment_gateway) {
            'bkash' => 'bg-pink-100 text-pink-800',
            'nagad' => 'bg-orange-100 text-orange-800',
            'sslcommerz' => 'bg-blue-100 text-blue-800',
            'cash' => 'bg-green-100 text-green-800',
            'bank_transfer' => 'bg-indigo-100 text-indigo-800',
            'cheque' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Check if donation can be updated
     */
    public function canBeUpdated()
    {
        return in_array($this->status, ['pending', 'failed']);
    }

    /**
     * Check if donation is successful
     */
    public function isSuccessful()
    {
        return $this->status === 'completed';
    }

    /**
     * Get display name for donor
     */
    public function getDonorDisplayNameAttribute()
    {
        if ($this->anonymous) {
            return 'Anonymous Donor';
        }
        
        return $this->donor_name ?? 'Guest';
    }
}