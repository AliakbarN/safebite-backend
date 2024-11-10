<?php

namespace App\Services;

use App\DTO\MealRecommendationFilters;
use App\Helpers\FormOpenaiJson;
use App\Models\UserData;
use App\Repositories\MealRepository;
use App\Services\Api\Openai;
use App\Services\MealRecommendationsGenerator\MessageGenerator;
use Illuminate\Support\Facades\Log;

class MealRecommendationsGenerator
{
    protected MessageGenerator $messageGenerator;
    protected Openai $openai;
    protected MealRepository $mealRepository;

    public function __construct(MessageGenerator $messageGenerator, Openai $openai, MealRepository $mealRepository)
    {
        $this->messageGenerator = $messageGenerator;
        $this->openai = $openai;
        $this->mealRepository = $mealRepository;
    }

    public function generate(MealRecommendationFilters $filters, int $userId): array
    {
        $userData = $this->getUserData($userId);
        $messages = $this->messageGenerator->generateMessages($filters, $userData);
        $data = $this->openai->chat($messages);
        Log::info('@DATA ' . $data);
        $formattedData = FormOpenaiJson::toArray($data);
        $meals = $this->mealRepository->bulkStore($formattedData);

        return $meals->toArray();
    }

    protected function getUserData(int $userId): UserData
    {
        return UserData::where('user_id', $userId)
            ->first();
    }
}
