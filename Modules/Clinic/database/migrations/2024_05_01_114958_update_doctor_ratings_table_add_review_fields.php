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
        Schema::table('doctor_ratings', function (Blueprint $table) {
            $table->string('name')->nullable()->after('service_id');
            $table->string('email')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->integer('age')->nullable()->after('phone');
            $table->string('treatments')->nullable()->after('age');
            $table->string('clinic_location')->nullable()->after('treatments');
            $table->string('referral_source')->nullable()->after('clinic_location');
            $table->string('referral_source_other')->nullable()->after('referral_source');
            $table->double('experience_rating')->nullable()->after('referral_source_other');
            $table->double('dentist_explanation')->nullable()->after('experience_rating');
            $table->double('pricing_satisfaction')->nullable()->after('dentist_explanation');
            $table->double('staff_courtesy')->nullable()->after('pricing_satisfaction');
            $table->double('treatment_satisfaction')->nullable()->after('staff_courtesy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_ratings', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'email',
                'phone',
                'age',
                'treatments',
                'clinic_location',
                'referral_source',
                'referral_source_other',
                'experience_rating',
                'dentist_explanation',
                'pricing_satisfaction',
                'staff_courtesy',
                'treatment_satisfaction',
            ]);
        });
    }
};