<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    protected $table = 'searches';

    protected $fillable = [
        'query',
        'count'
    ];

    protected $casts = [
        'count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Increment search count
     */
    public static function incrementCount($query)
    {
        if (empty($query)) return;

        $search = self::where('query', $query)->first();

        if ($search) {
            $search->increment('count');
        } else {
            self::create([
                'query' => $query,
                'count' => 1
            ]);
        }
    }

    /**
     * Get popular searches
     */
    public static function getPopular($limit = 10)
    {
        return self::orderBy('count', 'desc')->limit($limit)->get();
    }

    /**
     * Get recent searches
     */
    public static function getRecent($limit = 10)
    {
        return self::latest()->limit($limit)->get();
    }
}
