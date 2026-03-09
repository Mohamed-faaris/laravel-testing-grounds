<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'favorite_at',
    ];

    protected $casts = [
        'favorited_at' => 'datetime',
    ];

    public function isFavorited()
    {
        return $this->favorited_at !== null;
    }

    public function favorite()
    {
        $this->favorited_at = now();
        $this->save();
    }

    public function unfavorite()
    {
        $this->favorited_at = null;
        $this->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFavorited($query)
    {
        return $query->whereNotNull('favorited_at');
    }
}
