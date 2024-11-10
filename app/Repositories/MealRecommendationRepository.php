<?php

namespace App\Repositories;

use App\Models\MealRecommendation;
use App\Models\UserHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MealRecommendationRepository
{
    public function store(int $mealId, int $historyId): MealRecommendation
    {
        $recommendation = MealRecommendation::where('user_history_id', $historyId)->first();

        if (!$recommendation) {
            $recommendation = MealRecommendation::create([
                'meal_id' => $mealId,
                'user_history_id' => $historyId
            ]);
        }

        return $recommendation;
    }

    public function getCurrentRecommendations(UserHistory $history): Collection
    {
        $dayStart = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $dayEnd = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        return MealRecommendation::where('user_history_id', $history->id)
            ->whereBetween('created_at', [$dayStart, $dayEnd])
            ->get();
    }

}
