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
        Schema::create('sdo_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('sdo_code')->unique();
            $table->string('sdo_name');
            $table->string('sds_name')->nullable();
            $table->string('asds_name')->nullable();
            $table->enum('type',["city", "province"]);
            $table->string('password');
            $table->string('email')->unique();

            $table->enum('status',["active", "inactive"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdo_accounts');
    }
};
