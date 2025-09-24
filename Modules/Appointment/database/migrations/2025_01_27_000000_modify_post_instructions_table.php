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
        Schema::table('post_instructions', function (Blueprint $table) {
            $table->string('title')->after('id')->nullable();
            $table->string('procedure_type')->after('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_instructions', function (Blueprint $table) {
            $table->dropColumn(['title', 'procedure_type']);
        });
    }
};
