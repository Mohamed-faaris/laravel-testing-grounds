<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the user that owns the note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the note is published and publicly viewable.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if the note is pending admin review.
     */
    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }

    /**
     * Check if the note is a draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the note was rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Submit the note for publication review.
     */
    public function submitForReview(): bool
    {
        if ($this->isDraft()) {
            return $this->update(['status' => 'pending_review']);
        }

        return false;
    }

    /**
     * Approve the note for publication.
     */
    public function approve(): bool
    {
        if ($this->isPendingReview()) {
            return $this->update(['status' => 'published']);
        }

        return false;
    }

    /**
     * Reject the note.
     */
    public function reject(): bool
    {
        if ($this->isPendingReview()) {
            return $this->update(['status' => 'rejected']);
        }

        return false;
    }

    /**
     * Get status badge color for UI.
     */
    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'draft' => 'zinc',
            'pending_review' => 'amber',
            'published' => 'emerald',
            'rejected' => 'red',
            default => 'zinc',
        };
    }

    /**
     * Get status badge text for UI.
     */
    public function getStatusBadgeText(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending_review' => 'Pending Review',
            'published' => 'Published',
            'rejected' => 'Rejected',
            default => 'Unknown',
        };
    }
}
