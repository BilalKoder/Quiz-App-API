<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes;
    protected $table = 'quiz';
    protected $guarded = [];

    protected $fillable = [
        'title', 'description', 'state',
    ];

    public function questions()
    {
        return $this->hasMany('App\Models\QuizQuestions', 'quiz_id');
    }
}
