<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\MealCategoryController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserHistoryController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')
    ->prefix('user')
    ->group(function ()
    {
        Route::post('register', [AuthController::class, 'register'])
            ->withoutMiddleware('auth:api')
            ->name('user.register');

        Route::post('login', [AuthController::class, 'login'])
            ->withoutMiddleware('auth:api')
            ->name('user.login');

        Route::post('logout', [AuthController::class, 'logout'])
            ->name('user.logout');

        Route::get('getFavouriteMeals', [UserController::class, 'getFavoriteMeals'])
            ->name('user.getFavoriteMeals');

        Route::put('/', [UserController::class, 'update'])
            ->name('user.update');

        Route::get('/{user}', [UserController::class, 'show'])
            ->name('user.show');
    });

Route::middleware('auth:api')
    ->prefix('meals')
    ->group(function ()
    {
        Route::get('search', [MealController::class, 'search'])
            ->name('meals.search');

        Route::get('recommendations', [RecommendationController::class, 'getRecommendations'])
            ->name('meals.recommendations');

        Route::get('recommendations/update', [RecommendationController::class, 'updateRecommendation'])
            ->name('meals.recommendations.update');

        Route::get('favourite/add/{meal}', [MealController::class, 'addToFavorites'])
            ->name('meals.favourite.add');

        Route::get('favourite/remove/{meal}', [MealController::class, 'removeFromFavorites'])
            ->name('meals.favourite.remove');

        Route::get('categories', [MealCategoryController::class, 'index'])
            ->name('meals.categories.index');

        Route::get('confirm/{meal}', [UserHistoryController::class, 'confirmMeal'])
            ->name('meals.confirm');

        Route::get('getCurrentRecommendations', [UserHistoryController::class, 'getCurrentRecommendations'])
            ->name('meals.getCurrentRecommendations');
    });

Route::middleware('auth:api')
    ->prefix('chat')
    ->group(function ()
    {
        Route::post('/user/sendMessage', [ChatController::class, 'userSendMessage']);
        Route::get('/user/getChatHistory', [ChatController::class, 'getChatHistory']);
    });
