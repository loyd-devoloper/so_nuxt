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
        Schema::create('so_applications', function (Blueprint $table) {
            $table->id()->from('100002025');
            $table->unsignedBigInteger('curriculum_id');
            $table->unsignedBigInteger('school_id');

            $table->string("applied_track");
            $table->string("applied_strand");
            $table->json("applied_specialization")->nullable();
            $table->enum('status', ['pending', 'onprocess', 'overdue', 'approved', 'releasing', 'for_claim', 'released'])->default('pending');
            $table->unsignedBigInteger('form_checker')->nullable();
            $table->unsignedBigInteger('evaluation_checker')->nullable();
            $table->unsignedBigInteger('review_checker')->nullable();
            $table->unsignedBigInteger('approve_checker')->nullable();
            $table->unsignedBigInteger('signatory_id')->nullable();
            $table->boolean('is_form_checked')->default(false);
            $table->boolean('is_evaluation_checked')->default(false);
            $table->boolean('is_review_checked')->default(false);
            $table->boolean('is_approve_checked')->default(false);


            $table->date("graduation_date")->nullable();
            $table->date("date_granted")->nullable();
            $table->string("signed_so_path")->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->string("is_resubmit")->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_applications');
    }
};
