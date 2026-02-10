<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    protected $fillable = [
        'course_id',
        'order',
        'title',
        'body',
        'type',
        'content_url',
        'content_meta',
    ];

    protected function casts(): array
    {
        return [
            'content_meta' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'content_id', 'id');
    }

    public function endQuizzes(): HasMany
    {
        return $this->hasMany(EndQuiz::class, 'content_id', 'id');
    }

    public function modelQuizzes(): HasMany
    {
        return $this->hasMany(ModelQuiz::class, 'content_id', 'id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class, 'content_id', 'id');
    }

    public function speedLogs(): HasMany
    {
        return $this->hasMany(SpeedLog::class, 'content_id', 'id');
    }
}
