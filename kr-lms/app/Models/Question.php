<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'type',
        'question_text',
        'options',
        'correct_answer',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'correct_answer' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function endQuizzes(): HasMany
    {
        return $this->hasMany(EndQuiz::class, 'question_id', 'id');
    }

    public function modelQuizzes(): HasMany
    {
        return $this->hasMany(ModelQuiz::class, 'question_id', 'id');
    }
}
