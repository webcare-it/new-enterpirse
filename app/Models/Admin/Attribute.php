<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    public function attribute_values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
