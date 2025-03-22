<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class HD extends BaseModel
{
    use HasFactory;

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
