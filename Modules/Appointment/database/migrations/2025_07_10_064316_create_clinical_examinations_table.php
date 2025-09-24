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
        Schema::create('clinical_examinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_history_id');
            $table->string('face_symmetry')->nullable();
            $table->json('tmj_status')->nullable();
            $table->string('occlusion_bite')->nullable(); // Normal or Malocclusion
            $table->json('malocclusion_class')->nullable();
            $table->string('soft_tissue_status')->nullable();
            $table->text('teeth_status')->nullable();
            $table->string('gingival_health')->nullable();
            $table->boolean('bleeding_on_probing')->default(false);
            $table->string('pocket_depths')->nullable();
            $table->boolean('mobility')->default(false);
            $table->boolean('bruxism')->default(false);
            $table->timestamps();

            $table->foreign('patient_history_id')->references('id')->on('patient_histories')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_examinations');
    }
};
