<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register'])
    ->name('user.register');

Route::prefix('auth')
    ->middleware('auth:api') // Add this line to apply the middleware to all routes within the prefix
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])
            ->withoutMiddleware('auth:api') // Login should not require authentication
            ->name('user.login');

        Route::post('logout', [AuthController::class, 'logout'])
            ->name('user.logout');

        Route::post('user', [AuthController::class, 'user'])
            ->name('user.show');

        Route::put('user', [UserController::class, 'update'])
            ->name('user.update');
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

        Route::get('categories', [\App\Http\Controllers\Api\MealCategoryController::class, 'index'])
            ->name('meals.categories.index');

        Route::get('confirm/{meal}', [\App\Http\Controllers\Api\UserHistoryController::class, 'confirmMeal'])
            ->name('meals.confirm');

        Route::get('getCurrentRecommendations', [\App\Http\Controllers\Api\UserHistoryController::class, 'getCurrentRecommendations'])
            ->name('meals.getCurrentRecommendations');
    });
