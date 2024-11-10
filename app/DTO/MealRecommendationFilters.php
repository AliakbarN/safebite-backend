<?php

namespace App\DTO;

use App\Models\Meal;

class MealRecommendationFilters
{
    protected string $mode;
    protected string $mealType;
    protected ?array $usedMeals;

    public function __construct(string $mode, string $mealType, ?array $usedMeals = [])
    {
        $this->mode = $mode;
        $this->mealType = $mealType;
        $this->usedMeals = $this->fetchMeals($usedMeals);
    }

    /**
     * @return string
     */
    public function getMealType(): string
    {
        return $this->mealType;
    }

    /**
     * @return string
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @return array|null
     */
    public function getUsedMeals(): ?array
    {
        return $this->usedMeals;
    }

    /**
     * @param string $mealType
     * @return MealRecommendationFilters
     */
    public function setMealType(string $mealType): self
    {
        $this->mealType = $mealType;

        return $this;
    }

    /**
     * @param string $mode
     * @return MealRecommendationFilters
     */
    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @param array $usedMeals
     * @return MealRecommendationFilters
     */
    public function setUsedMeals(array $usedMeals): self
    {
        $this->usedMeals = $this->fetchMeals($usedMeals);

        return $this;
    }

    protected function fetchMeals(array $meals): array
    {
        return Meal::whereIn('id', $meals)->pluck('name')->toArray();
    }
}
