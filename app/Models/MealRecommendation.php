<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealRecommendation extends Model
{
    protected $fillable = [
        'meal_id',
        'user_history_id',
    ];

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function history(): BelongsTo
    {
        return $this->belongsTo(UserHistory::class);
    }
}
