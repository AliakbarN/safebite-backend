<?php

namespace App\Repositories;

use App\Models\MealCategory;
use Illuminate\Database\Eloquent\Collection;

class MealCategoryRepository
{
    public function index(): Collection
    {
        return MealCategory::all();
    }

    public function store(string $name): MealCategory
    {
        return MealCategory::firstOrCreate(['name' => $name]);
    }
}
