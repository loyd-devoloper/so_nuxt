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
            $table->uuid('id');
            $table->uuid('curriculum_id');
            $table->uuid('school_id');

            $table->string("applied_track");
            $table->string("applied_strand")->nullable();
            $table->json("applied_specialization")->nullable();
            $table->enum('status', ['pending', 'onprocess', 'overdue', 'approved', 'releasing', 'for_claim', 'released'])->default('pending');
            $table->uuid('form_checker')->nullable();
            $table->uuid('evaluation_checker')->nullable();
            $table->uuid('review_checker')->nullable();
            $table->uuid('approve_checker')->nullable();
            $table->uuid('signatory_id')->nullable();
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
