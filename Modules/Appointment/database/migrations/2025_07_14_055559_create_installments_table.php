<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_record_id');
            $table->integer('amount');
            $table->enum('payment_mode', ['cash', 'card', 'online'])->default('cash');
            $table->date('date')->nullable();
            $table->timestamps();

            $table->foreign('billing_record_id')->references('id')->on('billing_record')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
