<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//-------------------------------------------------------------------------------------------------
class SessionAnswer extends Model
{
    protected $fillable = [
        'session_id',
        'question_id',
        'answer_id',
    ];

    //---------------------------------------------------------------------------------------------
    public function session(): BelongsTo
    {
        return $this->belongsTo(QuizSession::class, 'session_id');
    }

    //---------------------------------------------------------------------------------------------
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    //---------------------------------------------------------------------------------------------
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}