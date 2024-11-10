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
        Schema::table('user_disease', function (Blueprint $table) {
            DB::statement('ALTER TABLE user_disease DROP CONSTRAINT IF EXISTS user_diseases_user_id_foreign');

            // Rename the column from user_id to user_data_id
            $table->renameColumn('user_id', 'user_data_id');

            // Add the new foreign key constraint linking to the user_data table
            $table->foreign('user_data_id')->references('id')->on('user_data')->onDelete('set null');
        });
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
