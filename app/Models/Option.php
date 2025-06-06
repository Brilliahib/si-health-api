<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends BaseModel
{
    use HasFactory;

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function userAnswerScreening()
    {
        return $this->hasMany(UserAnswerScreening::class, 'selected_option_id');
    }
}
