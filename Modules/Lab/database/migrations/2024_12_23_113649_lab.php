<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('labs', function (Blueprint $table) {
            $table->id();

            // Associations
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');

            // Case Details
            $table->string('case_type',255)->nullable();
            $table->text('notes')->nullable();
            $table->enum('case_status', ['created', 'in_progress', 'sent_to_lab', 'delivered', 'seated'])->default('created');
            $table->date('delivery_date_estimate')->nullable();

            // Impression Types
            $table->enum('clear_aligner_impression_type', ['physical', 'digital'])->nullable();
            $table->enum('prosthodontic_impression_type', ['physical', 'digital'])->nullable();
            // Treatment Plan
            $table->string('treatment_plan_link')->nullable();

            // Rx Instructions
            $table->string('margin_location')->nullable(); // e.g., supra, gingival
            $table->string('contact_tightness')->nullable();
            $table->string('occlusal_scheme')->nullable();
            $table->boolean('temporary_placed')->default(false);
            $table->json('teeth_treatment_type')->nullable(); // Multiple treatment types for teeth

            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();   
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('labs');
    }
};
