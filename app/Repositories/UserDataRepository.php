<?php

namespace App\Repositories;

use App\Models\Allergy;
use App\Models\Disease;
use App\Models\UserData;

class UserDataRepository
{
    public function getByUser(int $userId): ?UserData
    {
        return UserData::where('user_id', $userId)->first();
    }

    public function store(int $userId, int $age, ?array $allergies, ?array $diseases, int $weight, int $height, string $gender): UserData
    {
        $userData = UserData::create([
            'age' => $age,
            'weight' => $weight,
            'height' => $height,
            'gender' => $gender,
            'user_id' => $userId,
        ]);

        if ($diseases !== null) {
            $this->storeDiseaseRelations($userData, $diseases);
        }

        if ($allergies !== null) {
            $this->storeAllergyRelations($userData, $allergies);
        }

        return $userData;
    }

    public function update(int $userId, int $age, ?array $allergies, ?array $diseases, int $weight, int $height, string $gender): UserData
    {
        // Update existing UserData or create a new instance
        $userData = UserData::updateOrCreate(
            ['user_id' => $userId],
            [
                'age' => $age,
                'weight' => $weight,
                'height' => $height,
                'gender' => $gender,
            ]
        );

        if ($diseases !== null) {
            $this->storeDiseaseRelations($userData, $diseases);
        }

        if ($allergies !== null) {
            $this->storeAllergyRelations($userData, $allergies);
        }

        return $userData;
    }

    protected function storeDiseaseRelations(UserData $userData, array $diseases): void
    {
        $diseaseIds = [];

        foreach ($diseases as $diseaseName) {
            $disease = Disease::firstOrCreate(['name' => $diseaseName]);
            $diseaseIds[] = $disease->id; // Collect the IDs for syncing
        }

        $userData->diseases()->sync($diseaseIds);
    }

    protected function storeAllergyRelations(UserData $userData, array $allergies): void
    {
        $allergyIds = [];

        foreach ($allergies as $allergyName) {
            $allergy = Allergy::firstOrCreate(['name' => $allergyName]);
            $allergyIds[] = $allergy->id; // Collect the IDs for syncing
        }

        $userData->allergies()->sync($allergyIds);
    }
}
