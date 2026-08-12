<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * User Relation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Product Relation
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
