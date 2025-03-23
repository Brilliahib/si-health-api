<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTest extends BaseModel
{
    use HasFactory;

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
