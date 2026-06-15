<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    //

    public function questions() 
    {
        return $this->hasMany(Question::class);
    }
}
