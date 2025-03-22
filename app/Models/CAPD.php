<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CAPD extends BaseModel
{
    use HasFactory;

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
