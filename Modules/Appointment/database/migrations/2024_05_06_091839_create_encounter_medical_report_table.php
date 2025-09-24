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
        Schema::create('encounter_medical_report', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('encounter_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); 
        
            $table->string('name')->nullable();  
            $table->date('date')->nullable(); 
        
            $table->json('intraoral_scans')->nullable();
            $table->json('oral_pics')->nullable();
            $table->json('radiographs')->nullable(); // stores selected checkboxes
            $table->json('radiograph_files')->nullable();
            $table->json('additional_attachments')->nullable();
        
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
        Schema::dropIfExists('encounter_medical_report');
    }
};
