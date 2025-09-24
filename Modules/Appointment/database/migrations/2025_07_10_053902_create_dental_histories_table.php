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
        Schema::create('dental_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_history_id');
            $table->date('last_dental_visit_date')->nullable();
            $table->text('reason_for_last_visit')->nullable();
            $table->json('issues_experienced')->nullable();
            $table->tinyInteger('dental_anxiety_level')->nullable(); // 1 to 5
            $table->timestamps();

            $table->foreign('patient_history_id')->references('id')->on('patient_histories')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dental_histories');
    }
};
