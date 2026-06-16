<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//-------------------------------------------------------------------------------------------------
class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'text',
        'type',
    ];

    //---------------------------------------------------------------------------------------------
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    //---------------------------------------------------------------------------------------------
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }   
}
