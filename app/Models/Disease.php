<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Disease extends Model
{
    protected $fillable = [
        'name'
    ];

    public function userData(): BelongsToMany
    {
        return $this->belongsToMany(UserData::class, 'user_disease');
    }
}
