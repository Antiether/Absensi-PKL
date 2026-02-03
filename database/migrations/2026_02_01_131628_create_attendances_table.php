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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('checkin_time')->nullable();
            $table->time('checkout_time')->nullable();
            $table->double('checkin_lat')->nullable();
            $table->double('checkin_lng')->nullable();
            $table->double('checkout_lat')->nullable();
            $table->double('checkout_lng')->nullable();
            $table->string('checkin_photo')->nullable();
            $table->string('checkout_photo')->nullable();
            $table->string('status')->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
