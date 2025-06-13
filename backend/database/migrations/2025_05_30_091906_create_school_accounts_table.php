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
        Schema::create('school_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('school_number');
            $table->unsignedBigInteger('sdo_id');
            $table->text('school_name')->nullable();
            $table->text('school_address')->nullable();
            $table->text('school_head_name')->nullable();
            $table->text('owner_name')->nullable();
            $table->text('school_contact_number', 1000)->nullable();
            $table->text('school_email_address', 1000)->nullable();
            $table->date('date_established')->nullable();
            $table->text('admin_first_name')->nullable();
            $table->text('admin_last_name')->nullable();
            $table->text('admin_email_address')->unique()->nullable();
            $table->text('admin_contact_number')->nullable();
            $table->text('admin_suffix')->nullable();
            $table->text('admin_middle_name')->nullable();
            $table->string('password');
            $table->enum('status', ['pending', 'validating', 'approved'])->default('pending');
            $table->boolean('is_first_time_login')->default(1)->comment('0 = false, 1 = true; only for school users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_accounts');
    }
};
