<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    protected $fillable = [
        'user_id',
    ];

    protected static function booted(): void
    {
        static::created(function (Like $like) {
            if ($like->likeable instanceof Post) {
                $like->likeable->increment('likes_count');
            }
        });

        static::deleted(function (Like $like) {
            if ($like->likeable instanceof Post) {
                $like->likeable->decrement('likes_count');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
