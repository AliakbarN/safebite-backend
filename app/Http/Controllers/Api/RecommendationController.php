<?php

namespace App\Http\Controllers\Api;

use App\DTO\MealRecommendationFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecommendationRequest;
use App\Models\User;
use App\Services\MealRecommendationsGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    protected ResponseFactory|Response $response;
    protected MealRecommendationsGenerator $recommendationsGenerator;

    public function __construct(MealRecommendationsGenerator $recommendationsGenerator)
    {
        $this->response = response();
        $this->recommendationsGenerator = $recommendationsGenerator;
    }

    public function getRecommendations(RecommendationRequest $request): JsonResponse
    {
        $mealType = $request->query('mealType', 'notvegan');
        $mode = $request->query('mode', 'stable');
        $user = Auth::user();

        $filters = new MealRecommendationFilters($mode, $mealType);

        $recommendations = $this->recommendationsGenerator->generate($filters, $user->id);

        return $this->response->json([
            'data' => $recommendations,
        ]);
    }

    public function updateRecommendation(RecommendationRequest $request): JsonResponse
    {
        $mealType = $request->query('mealType', 'notvegan');
        $mode = $request->query('mode', 'stable');
        $usedMeals = $request->get('usedMeals') ?? [];
        $user = Auth::user();

        $filters = new MealRecommendationFilters($mode, $mealType, $usedMeals);

        $recommendations = $this->recommendationsGenerator->generate($filters, $user->id);

        return $this->response->json([
            'data' => $recommendations,
        ]);
    }
}
