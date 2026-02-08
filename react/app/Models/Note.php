<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

    // Status constants
    public const STATUS_PRIVATE = 'private';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'status',
        'published_at',
        'reviewed_by',
        'review_notes',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePrivate($query)
    {
        return $query->where('status', self::STATUS_PRIVATE);
    }

    public function scopePublic($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    // Helper methods
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPrivate(): bool
    {
        return $this->status === self::STATUS_PRIVATE;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBePublished(): bool
    {
        return in_array($this->status, [self::STATUS_PRIVATE, self::STATUS_REJECTED]);
    }
}
