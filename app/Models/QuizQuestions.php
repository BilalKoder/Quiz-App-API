<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizQuestions extends Model
{
    use SoftDeletes;
    protected $table = 'quiz_questions';
    protected $guarded = [];

    public function answers()
    {
        return $this->hasMany('App\Models\QuizAnswers', 'question_id');
    }
}
