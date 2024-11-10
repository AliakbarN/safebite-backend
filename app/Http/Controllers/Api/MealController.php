<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MealSearchRequest;
use App\Models\Meal;
use App\Repositories\MealRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;

class MealController extends Controller
{
    protected MealRepository $mealRepository;
    protected Response|ResponseFactory $response;

    public function __construct(MealRepository $mealRepository)
    {
        $this->mealRepository = $mealRepository;
        $this->response = response();
    }

    public function search(MealSearchRequest $request): JsonResponse
    {
        $searchResult = $this->mealRepository->search(
            $request->get('search'),
            $request->get('categoryId'),
            $request->get('mealType'),
            $request->get('mode'),
        );

        return $this->response->json([
            'data' => $searchResult,
        ]);
    }

    public function addToFavorites(Request $request, Meal $meal): JsonResponse
    {
        $user = Auth::user(); // Get the authenticated user

        if ($this->mealRepository->storeToFavorites($user, $meal)) {
            return $this->response->json(['message' => 'Meal added to favorites successfully.']);
        }

        return $this->response->json(['message' => 'Meal is already in favorites.'], 409);
    }

    public function removeFromFavorites(Request $request, Meal $meal): JsonResponse
    {
        $user = Auth::user(); // Get the authenticated user

        if ($this->mealRepository->removeFromFavorites($user, $meal)) {
            return $this->response->json(['message' => 'Meal removed from favorites successfully.']);
        }

        return $this->response->json(['message' => 'Meal was not found in favorites.'], 404);
    }
}
