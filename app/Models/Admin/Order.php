<?php

namespace App\Models\Admin;

use App\Models\Dropshipper;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stripe\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'manual_payment' => 'boolean',
        'viewed' => 'boolean',
        'delivery_viewed' => 'boolean',
        'payment_status_viewed' => 'boolean',
        'commission_calculated' => 'boolean',
        'grand_total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    /**
     * Order has many order details
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * User who placed the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Seller
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }


    /**
     * Get the orders for the user
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    /**
     * Get the order details (items) for this order.
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function dropshipper()
    {
        return $this->belongsTo(Dropshipper::class, 'dropshipper_id');
    }
}
