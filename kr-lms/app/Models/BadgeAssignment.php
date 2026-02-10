<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BadgeAssignment extends Model
{
    protected $table = 'badge_assignments';

    protected $fillable = [
        'user_id',
        'badge_id',
    ];

    protected $primaryKey = ['user_id', 'badge_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class, 'badge_id', 'id');
    }
}
