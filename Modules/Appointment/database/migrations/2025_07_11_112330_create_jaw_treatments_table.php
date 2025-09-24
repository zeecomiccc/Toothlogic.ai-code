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
    Schema::create('jaw_treatments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('patient_history_id')->unique();
        $table->text('upper_jaw_treatment')->nullable();
        $table->text('lower_jaw_treatment')->nullable();
        $table->timestamps();

        $table->foreign('patient_history_id')
              ->references('id')->on('patient_histories')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jaw_treatments');
    }
};
