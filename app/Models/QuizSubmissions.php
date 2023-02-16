<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizSubmissions extends Model
{
    use SoftDeletes;
    protected $table = 'quiz_entries';
    protected $guarded = [];
}
