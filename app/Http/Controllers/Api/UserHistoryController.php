<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Repositories\MealRecommendationRepository;
use App\Repositories\UserDataRepository;
use App\Repositories\UserHistoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;

class UserHistoryController extends Controller
{
    protected Response|ResponseFactory $response;
    protected UserHistoryRepository $userHistoryRepository;
    protected MealRecommendationRepository $mealRecommendationRepository;

    public function __construct(UserHistoryRepository $userHistoryRepository, MealRecommendationRepository $mealRecommendationRepository)
    {
        $this->response = response();
        $this->userHistoryRepository = $userHistoryRepository;
        $this->mealRecommendationRepository = $mealRecommendationRepository;
    }


    public function confirmMeal(Request $request, Meal $meal): JsonResponse
    {
        $user = Auth::user();

        $history = $this->userHistoryRepository->store($user->id);
        $this->mealRecommendationRepository->store($meal->id, $history->id);

        return $this->response->json([
            'message' => 'History successfully stored',
        ]);
    }

    public function getCurrentRecommendations(): JsonResponse
    {
        $user = Auth::user();

        $userHistory = $this->userHistoryRepository->getHistory($user->id);
        $recommendedMeals = $this->mealRecommendationRepository->getCurrentRecommendations($userHistory);

        return $this->response->json([
            'data' => $recommendedMeals,
        ]);
    }
}
