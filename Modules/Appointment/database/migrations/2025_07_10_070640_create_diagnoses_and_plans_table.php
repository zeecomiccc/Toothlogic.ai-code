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
        Schema::create('diagnoses_and_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_history_id');

            $table->text('diagnosis')->nullable();
            $table->json('proposed_treatments')->nullable();
            $table->text('planned_timeline')->nullable();

            $table->boolean('alternatives_discussed')->default(false);
            $table->boolean('risks_explained')->default(false);
            $table->boolean('questions_addressed')->default(false);

            $table->timestamps();

            $table->foreign('patient_history_id')->references('id')->on('patient_histories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses_and_plans');
    }
};
