<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class IncompleteOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'cart_data' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'last_activity' => 'datetime',
        'abandoned_at' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
    ];

    /**
     * Get the order associated with the incomplete order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who created the incomplete order
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the incomplete order
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope for pending incomplete orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for abandoned orders
     */
    public function scopeAbandoned($query)
    {
        return $query->where('status', 'abandoned');
    }

    /**
     * Scope for orders older than given days
     */
    public function scopeOlderThan($query, $days)
    {
        return $query->where('created_at', '<', now()->subDays($days));
    }

    /**
     * Check if order is abandoned
     */
    public function isAbandoned()
    {
        return $this->status === 'abandoned';
    }

    /**
     * Check if order is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get abandoned duration in minutes
     */
    public function getAbandonedDurationAttribute()
    {
        if ($this->abandoned_at) {
            return $this->abandoned_at->diffInMinutes(now());
        }
        return null;
    }

    /**
     * Get formatted abandoned duration
     */
    public function getFormattedAbandonedDurationAttribute()
    {
        if ($this->abandoned_at) {
            $minutes = $this->abandoned_at->diffInMinutes(now());

            if ($minutes < 60) {
                return $minutes . ' ' . trans('minutes');
            } elseif ($minutes < 1440) {
                return floor($minutes / 60) . ' ' . trans('hours');
            } else {
                return floor($minutes / 1440) . ' ' . trans('days');
            }
        }
        return '-';
    }
}
