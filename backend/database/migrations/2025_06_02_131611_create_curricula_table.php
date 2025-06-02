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
        Schema::create('curricula', function (Blueprint $table) {
            $table->id();
            $table->year('school_year_start');
            $table->year('school_year_end');
            $table->enum('is_open_for_so_application',["Open", "Close"])->default("Open");
            $table->string('regional_director')->nullable();
            $table->string('assistant_regional_director')->nullable();
            $table->timestamp('open_date')->nullable();
            $table->timestamp('closing_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curricula');
    }
};
