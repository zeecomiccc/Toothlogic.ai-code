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
        Schema::create('promotions_coupon', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code')->unique();
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->boolean('is_expired')->default(0);
            $table->string('discount_type');
            $table->double('discount_percentage')->nullable();
            $table->double('discount_amount')->nullable();
            $table->integer('use_limit')->default(1);
            $table->integer('used_by')->nullable();
            $table->integer('promotion_id');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions_coupon');
    }
};
