<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', [\App\Http\Controllers\Api\RecommendationController::class, 'getRecommendations']);
Route::post('test1', [\App\Http\Controllers\Api\RecommendationController::class, 'updateRecommendation']);
