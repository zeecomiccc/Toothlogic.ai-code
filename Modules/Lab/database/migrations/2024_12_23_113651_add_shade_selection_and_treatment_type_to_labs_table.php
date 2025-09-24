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
        Schema::table('labs', function (Blueprint $table) {
            $table->json('teeth_treatment_type')->nullable()->after('treatment_plan_link');
            $table->string('shade_selection')->nullable()->after('teeth_treatment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            $table->dropColumn('shade_selection');
            $table->dropColumn('teeth_treatment_type');
        });
    }
};
