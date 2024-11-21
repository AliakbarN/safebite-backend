<?php

namespace App\Repositories;

use App\Helpers\FormSearchResult;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;

class MealRepository
{
    public function search(?string $search, ?string $category, ?string $mealType, ?string $mode): array
    {
        $searchResult = (new Search())
            ->registerModel(Meal::class, function (ModelSearchAspect $modelSearchAspect) use ($search, $mealType, $mode) {
                if ($search) {
                    $modelSearchAspect
                        ->addSearchableAttribute('name')
                        ->addSearchableAttribute('recipe');
                }

                if ($mealType) {
                    $modelSearchAspect->addSearchableAttribute('meal_type');
                }

                if ($mode) {
                    $modelSearchAspect->addSearchableAttribute('mode');
                }
            });

        if ($search) {
            $searchResult = $searchResult
                ->limitAspectResults(25)
                ->search($search);

            // Filter by category after search if provided
            if ($category) {
                $searchResult = $searchResult->filter(function ($result) use ($category) {
                    return $result->searchable->category_id == $category;
                });
            }
        } elseif ($category) {
            // If no search term, filter meals by category only
            $searchResult = Meal::whereHas('category', function ($query) use ($category) {
                $query->where('id', $category);
            })->get();

            return $searchResult->toArray();
        } else {
            $searchResult = [];
        }

        return FormSearchResult::form($searchResult);
    }

    public function store(string $name, string $recipe, array $ingredients): Meal
    {
        return Meal::create([
            'name' => $name,
            'recipe' => $recipe,
            'ingredients' => json_encode($ingredients),
        ]);
    }

    public function bulkStore(array $meals): Collection|bool
    {
        $categoryRepository = new MealCategoryRepository();

        foreach ($meals as $meal) {
            if (!isset($meal['name'], $meal['recipe'], $meal['ingredients'])) {
                throw new \InvalidArgumentException('Each meal must have a name, recipe, and ingredients.');
            }
        }

        $mealData = array_map(function ($meal) use ($categoryRepository)
        {
            return [
                'name' => $meal['name'],
                'recipe' => $meal['recipe'],
                'ingredients' => json_encode($meal['ingredients']),
                'mode' => $meal['mode'],
                'meal_type' => $meal['meal_type'],
                'category_id' => $categoryRepository->store($meal['category'])->id,
            ];
        }, $meals);

        try {
            Meal::insert($mealData);
            return Meal::latest('created_at')->take(count($meals))->get();
        } catch (\Exception $e) {
            \Log::error('Bulk store failed: ' . $e->getMessage());
            return false;
        }
    }

    public function storeToFavorites(User $user, Meal $meal): bool
    {
        if ($user->favoriteMeals()->where('meal_id', $meal->id)->exists()) {
            return false;
        }

        $user->favoriteMeals()->attach($meal->id);

        return true;
    }

    public function removeFromFavorites(User $user, Meal $meal): bool
    {
        if ($user->favoriteMeals()->detach($meal->id)) {
            return true;
        }

        return false;
    }

    public function getFavourited(User $user): Collection
    {
        return $user->favoriteMeals()->get();
    }
}
