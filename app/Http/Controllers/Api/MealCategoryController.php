<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MealCategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class MealCategoryController extends Controller
{
    protected Response|ResponseFactory $response;
    protected MealCategoryRepository $categoryRepository;

    public function __construct(MealCategoryRepository $categoryRepository)
    {
        $this->response = response();
        $this->categoryRepository = $categoryRepository;
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->index();

        return $this->response->json([
            'data' => $categories,
        ]);
    }
}
