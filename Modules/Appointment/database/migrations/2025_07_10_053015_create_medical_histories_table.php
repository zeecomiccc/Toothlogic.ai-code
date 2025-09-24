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
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_history_id');
            $table->boolean('under_medical_treatment')->default(false);
            $table->text('treatment_details')->nullable();
            $table->boolean('hospitalized_last_year')->default(false);
            $table->text('hospitalization_reason')->nullable();
            $table->json('diseases')->nullable();
            $table->string('pregnant_or_breastfeeding')->nullable();
            $table->json('taking_medications')->nullable();
            $table->json('known_allergies')->nullable();
            $table->timestamps();

            $table->foreign('patient_history_id')->references('id')->on('patient_histories')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
