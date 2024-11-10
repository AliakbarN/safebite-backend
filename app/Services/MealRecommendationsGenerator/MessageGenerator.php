<?php

namespace App\Services\MealRecommendationsGenerator;

use App\DTO\MealRecommendationFilters;
use App\DTO\OpenaiMessage;
use App\Models\UserData;
use Illuminate\Support\Facades\Log;

class MessageGenerator
{
    protected string $systemMessage = 'You are a nutritionist. Please respond in json format: name (meal name), recipe, ingredients, mode (loose (to loose weight), gain (to gain weight), stable (to maintain weight)), meal_type (vegan, notvegan), category (meal category, like sea food, fast food and etc.). Give 5 meals at least.';
    protected string $queryPattern = "
        Suggest a dish for a person {who is {mealType}} {with [diseases]]},
        {who needs to avoid [allergies]},
        {who wants {mode}},
        {who is {age}},
        {who is {gender}}.
        {do not recommend [usedMeals]}
    ";

    public function generateMessages(MealRecommendationFilters $filters, UserData $userData): OpenaiMessage
    {
        $message = new OpenaiMessage();
        $generatedMessage = $this->formMessage(
            $filters->getMealType(),
            $userData->diseases()->get()->pluck('name')->toArray(),
            $userData->allergies()->get()->pluck('name')->toArray(),
            $filters->getMode(),
            $userData->age,
            $userData->gender,
            $filters->getUsedMeals(), // returns array of meals' name
            $this->queryPattern,
        );

        Log::info('@MESSAGE ' . $generatedMessage);

        $message
            ->addMessage('system', $this->systemMessage)
            ->addMessage('user', $generatedMessage);

        return $message;
    }

    protected function formMessage(
        ?string $mealType = null,
        ?array $diseases = null,
        ?array $allergies = null,
        ?string $mode = null,
        ?int $age = null,
        ?string $gender = null,
        ?array $usedMeals = null,
        string $customPattern = null
    ): string {
        // Use helper method to map values if necessary
        $mealType = $this->mapData($mealType);
        $mode = $this->mapData($mode);
        $gender = $this->mapData($gender);

        // Prepare message parts based on provided data
        $messageParts = ["Suggest a dish for a person"];

        if ($mealType) {
            $messageParts[] = "who is $mealType";
        }

        if ($diseases && !empty($diseases)) {
            $messageParts[] = "with " . implode(', ', $diseases);
        }

        if ($allergies && !empty($allergies)) {
            $messageParts[] = "who needs to avoid " . implode(', ', $allergies);
        }

        if ($mode) {
            $messageParts[] = "who wants $mode";
        }

        if ($age) {
            $messageParts[] = "who is $age";
        }

        if ($gender) {
            $messageParts[] = "who is $gender";
        }

        if ($usedMeals && !empty($usedMeals)) {
            $messageParts[] = "do not recommend " . implode(', ', $usedMeals);
        }

        // Combine all message parts into a single string
        $generatedMessage = implode(', ', $messageParts);

        // Ensure the message starts properly formatted and ends without trailing punctuation
        $generatedMessage = ucfirst(trim($generatedMessage));
        $generatedMessage = rtrim($generatedMessage, ',.;') . '.';

        return $generatedMessage;
    }

    protected function mapData(?string $key): ?string {
        $map = [
            'loose' => 'to lose weight',
            'gain' => 'to gain weight',
            'stable' => 'to maintain weight',
            'm' => 'male',
            'f' => 'female',
            'notvegan' => 'not vegan',
        ];

        return $key && array_key_exists($key, $map) ? $map[$key] : $key;
    }

}
