<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'country',
        'city',
        'postal_code',
        'balance',
        'banned'
    ];

    protected $casts = [
        'banned' => 'boolean',
        'balance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the customer profile
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get customer full name
     */
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->name : 'N/A';
    }

    /**
     * Get customer email
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : 'N/A';
    }

    /**
     * Scope for banned customers
     */
    public function scopeBanned($query)
    {
        return $query->where('banned', true);
    }

    /**
     * Scope for active customers
     */
    public function scopeActive($query)
    {
        return $query->where('banned', false);
    }


    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}
