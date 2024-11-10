<?php

namespace App\Providers;

use App\Repositories\MealRepository;
use App\Services\Api\Openai;
use App\Services\MealRecommendationsGenerator;
use App\Services\MealRecommendationsGenerator\MessageGenerator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Openai::class, function ($app)
        {
            $key = env('OPENAI_KEY');

            return new Openai($key);
        });

        $this->app->singleton(MessageGenerator::class, function (Application $app)
        {
            return new MessageGenerator();
        });

        $this->app->singleton(MealRecommendationsGenerator::class, function (Application $app)
        {
            return new MealRecommendationsGenerator(
                $app->make(MessageGenerator::class),
                $app->make(Openai::class),
                $app->make(MealRepository::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
