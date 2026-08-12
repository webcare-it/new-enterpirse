<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compare extends Model
{
    use HasFactory;

    protected $table = 'compares';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the compare
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the product that is being compared
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get product with all relations
     */
    public function productWithRelations()
    {
        return $this->product()->with([
            'inventory',
            'price',
            'brand',
            'category',
            'reviews'
        ]);
    }

    /**
     * Scope for user compares
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for guest compares
     */
    public function scopeForIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }
}
