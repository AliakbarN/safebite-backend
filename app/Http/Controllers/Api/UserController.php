<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
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

    public function __construct(UserRepository $userRepository, UserDataRepository $userDataRepository)
    {
        $this->userRepository = $userRepository;
        $this->response = response();
        $this->userDataRepository = $userDataRepository;
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
}
