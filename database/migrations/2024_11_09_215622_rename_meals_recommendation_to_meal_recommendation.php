<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('meals_recommendation', 'meal_recommendation');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_recommendation', function (Blueprint $table) {
            //
        });
    }
};
