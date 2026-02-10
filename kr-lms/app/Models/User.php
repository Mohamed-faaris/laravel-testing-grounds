<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The primary key type is a string.
     */
    protected $keyType = 'string';

    /**
     * Auto incrementing is disabled.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'college',
        'department',
        'role',
        'email_verified',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified' => 'boolean',
            'password' => 'hashed',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user's metadata.
     */
    public function meta(): HasOne
    {
        return $this->hasOne(UserMeta::class, 'user_id', 'id');
    }

    /**
     * Get user sessions.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id', 'id');
    }

    /**
     * Get user accounts.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'user_id', 'id');
    }

    /**
     * Get user comments.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    /**
     * Get user enrollments.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id', 'id');
    }

    /**
     * Get user progress.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class, 'user_id', 'id');
    }

    /**
     * Get user feedback.
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'user_id', 'id');
    }

    /**
     * Get user speed logs.
     */
    public function speedLogs(): HasMany
    {
        return $this->hasMany(SpeedLog::class, 'user_id', 'id');
    }

    /**
     * Get user streaks.
     */
    public function streaks(): HasMany
    {
        return $this->hasMany(Streak::class, 'user_id', 'id');
    }

    /**
     * Get user XP.
     */
    public function xp(): HasOne
    {
        return $this->hasOne(Xp::class, 'user_id', 'id');
    }

    /**
     * Get user XP logs.
     */
    public function xpLogs(): HasMany
    {
        return $this->hasMany(XpLog::class, 'user_id', 'id');
    }

    /**
     * Get user badges.
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'badge_assignments', 'user_id', 'badge_id')
            ->withTimestamps('assigned_at');
    }

    /**
     * Get user notifications.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    /**
     * Get user quiz attempts.
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'user_id', 'id');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
