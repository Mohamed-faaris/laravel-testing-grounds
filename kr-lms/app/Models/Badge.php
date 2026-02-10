<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    protected $fillable = [
        'image',
        'title',
        'description',
        'conditions',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'badge_assignments', 'badge_id', 'user_id')
            ->withTimestamps('assigned_at');
    }
}
