<?php

namespace App\Models\Admin;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'discount_amount',
        'discount_type',
        'status',
        'image'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the products in this campaign
     */
    public function campaignProducts()
    {
        return $this->hasMany(CampaignProduct::class, 'campaign_id');
    }

    /**
     * Get the products through campaign_products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'campaign_products', 'campaign_id', 'product_id')
            ->withPivot('priority')
            ->withTimestamps();
    }

    /**
     * Get active campaigns
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get campaigns that are currently running
     */
    public function scopeRunning($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Get campaigns that haven't started yet
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '>', now());
    }

    /**
     * Get campaigns that have ended
     */
    public function scopeEnded($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'inactive')
                ->orWhere('end_date', '<', now());
        });
    }

    /**
     * Check if campaign is active
     */
    public function isActive()
    {
        return $this->status == 'active';
    }

    /**
     * Check if campaign is currently running
     */
    public function isRunning()
    {
        return $this->status == 'active' &&
            $this->start_date <= now() &&
            $this->end_date >= now();
    }

    /**
     * Check if campaign has started
     */
    public function hasStarted()
    {
        return $this->start_date <= now();
    }

    /**
     * Check if campaign has ended
     */
    public function hasEnded()
    {
        return $this->end_date < now();
    }

    /**
     * Alias for hasEnded() method
     */
    public function isEnded()
    {
        return $this->hasEnded();
    }

    /**
     * Check if campaign is upcoming (not started yet)
     */
    public function isUpcoming()
    {
        return $this->start_date > now();
    }

    /**
     * Get campaign progress percentage
     */
    public function getProgressPercentage()
    {
        if ($this->isUpcoming()) {
            return 0;
        }

        if ($this->hasEnded()) {
            return 100;
        }

        $totalDays = $this->start_date->diffInDays($this->end_date);
        $elapsedDays = $this->start_date->diffInDays(now());

        if ($totalDays <= 0) {
            return 0;
        }

        return min(100, max(0, ($elapsedDays / $totalDays) * 100));
    }

    /**
     * Get remaining days
     */
    public function getRemainingDays()
    {
        if ($this->hasEnded()) {
            return 0;
        }

        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Get elapsed days
     */
    public function getElapsedDays()
    {
        if ($this->isUpcoming()) {
            return 0;
        }

        if ($this->hasEnded()) {
            return $this->start_date->diffInDays($this->end_date);
        }

        return $this->start_date->diffInDays(now());
    }

    /**
     * Get total days of campaign
     */
    public function getTotalDays()
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get discount display text
     */
    public function getDiscountDisplayAttribute()
    {
        if (!$this->discount_amount) return 'N/A';

        if ($this->discount_type == 'percent') {
            return $this->discount_amount . '% OFF';
        }

        return '৳' . number_format($this->discount_amount, 2) . ' OFF';
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->isRunning()) {
            return '<span class="badge badge-success">Running</span>';
        } elseif ($this->isUpcoming()) {
            return '<span class="badge badge-info">Upcoming</span>';
        } elseif ($this->hasEnded()) {
            return '<span class="badge badge-secondary">Ended</span>';
        } elseif ($this->status == 'inactive') {
            return '<span class="badge badge-danger">Inactive</span>';
        }

        return '<span class="badge badge-warning">' . ucfirst($this->status) . '</span>';
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'photos');
    }
}
