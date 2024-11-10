<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserData extends Model
{
    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'height',
        'weight'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function diseases(): BelongsToMany
    {
        return $this->belongsToMany(Disease::class, 'user_disease');
    }

    public function allergies(): BelongsToMany
    {
        return $this->belongsToMany(Allergy::class, 'user_allergy');
    }
}
