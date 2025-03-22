<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends BaseModel
{
    use HasFactory;

    public function HDs()
    {
        return $this->hasMany(HD::class);
    }

    public function CAPDs()
    {
        return $this->hasMany(CAPD::class);
    }

    public function postTests()
    {
        return $this->hasMany(PostTest::class);
    }

    public function preTests()
    {
        return $this->hasMany(PreTest::class);
    }
}
