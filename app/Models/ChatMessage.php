<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'admin_id',
        'question',
        'answer',
        'intent',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
