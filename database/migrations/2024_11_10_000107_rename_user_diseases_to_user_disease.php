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
        Schema::rename('user_diseases', 'user_disease');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_disease', function (Blueprint $table) {
            //
        });
    }
};
