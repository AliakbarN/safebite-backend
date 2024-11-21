<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\MealRepository;
use App\Repositories\UserDataRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected Response|ResponseFactory $response;
    protected UserRepository $userRepository;
    protected UserDataRepository $userDataRepository;
    protected MealRepository $mealRepository;

    public function __construct
    (
        UserRepository $userRepository,
        UserDataRepository $userDataRepository,
        MealRepository $mealRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->response = response();
        $this->userDataRepository = $userDataRepository;
        $this->mealRepository = $mealRepository;
    }

    public function show(User $user): JsonResponse
    {
        $userData = $this->userDataRepository->getByUser($user->id);

        return $this->response->json(
            [
                'data' => [
                    'user' => $user,
                    'data' => $userData,
                ]
            ]
        );
    }

    public function update(UserRequest $request): JsonResponse
    {
        $user = Auth::user();

        if ($user === null) {
            return $this->response->json(
                [
                    'message' => 'Unauthorized',
                ],
                403
            );
        }

        // Update user attributes
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        $this->userDataRepository->update(
            $user->id,
            $request->get('age'),
            $request->get('allergies'),
            $request->get('diseases'),
            $request->get('weight'),
            $request->get('height'),
            $request->get('gender'),
        );

        return $this->response->json([
            'message' => 'User update successfully',
        ]);
    }

    public function getFavoriteMeals(): JsonResponse
    {
        $user = Auth::user();

        $favouriteMeals = $this->mealRepository->getFavourited($user);

        return $this->response->json([
            'data' => $favouriteMeals,
        ]);
    }
}
