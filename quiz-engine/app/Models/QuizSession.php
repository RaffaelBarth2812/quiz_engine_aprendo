<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// https://laravel.com/docs/13.x/eloquent-relationships#one-to-many
//-------------------------------------------------------------------------------------------------
class QuizSession extends Model
{
    protected $fillable = [
        'quiz_id',
        'started_at',
        'finished_at'
    ];

    protected $casts = [ 'started_at' => 'datetime', 'finished_at' => 'datetime', ];

    //---------------------------------------------------------------------------------------------
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    //---------------------------------------------------------------------------------------------
    public function answers(): HasMany
    {
        return $this->hasMany(SessionAnswer::class, 'session_id');
    }
}