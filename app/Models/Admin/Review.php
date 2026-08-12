<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'status',
        'is_read'
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_read' => 'boolean',
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the product that owns the review
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the user that owns the review
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scope for unread reviews
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    /**
     * Scope for high rating (4-5 stars)
     */
    public function scopeHighRating($query)
    {
        return $query->where('rating', '>=', 4);
    }

    /**
     * Scope for low rating (1-3 stars)
     */
    public function scopeLowRating($query)
    {
        return $query->where('rating', '<=', 3);
    }

    /**
     * Get rating as star HTML
     */
    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="las la-star text-warning"></i>';
            } else {
                $stars .= '<i class="lar la-star text-muted"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->status == 1) {
            return '<span class="badge badge-success">Approved</span>';
        }
        return '<span class="badge badge-warning">Pending</span>';
    }

    /**
     * Get read status badge HTML
     */
    public function getReadBadgeAttribute()
    {
        if ($this->is_read == 1) {
            return '<span class="badge badge-info">Read</span>';
        }
        return '<span class="badge badge-secondary">Unread</span>';
    }
}
