<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Meal extends Model implements Searchable
{
    protected $fillable = [
        'name',
        'recipe',
        'ingredients',
        'mode',
        'category_id',
        'meal_type',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MealCategory::class);
    }

    public function recommendation(): HasMany
    {
        return $this->hasMany(MealRecommendation::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('meals.recommendations', 'Meals');

        return new SearchResult(
            $this,
            'Meals',
            $url
        );
    }
}
