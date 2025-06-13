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
        Schema::create('program_offereds', function (Blueprint $table) {
            $table->id();
            $table->uuid('school_id');
            $table->string('track')->nullable();
            $table->string('strand')->nullable();;
            $table->json('specialization')->nullable();;
            $table->boolean('is_available')->default(true);
            $table->boolean('is_qad_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_offereds');
    }
};
