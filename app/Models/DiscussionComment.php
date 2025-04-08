<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscussionComment extends BaseModel
{
    use HasFactory;

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
