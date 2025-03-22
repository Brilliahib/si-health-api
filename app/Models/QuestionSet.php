<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSet extends BaseModel
{
    use HasFactory;

    public function testable()
    {
        return $this->morphTo();
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
