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
        Schema::create('program_lists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('curriculum_id');
            $table->string('track');
            $table->string('track_key');
            $table->json('strand');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_lists');
    }
};
