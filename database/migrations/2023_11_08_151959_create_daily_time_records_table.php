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
        Schema::create('daily_time_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biometric_id')->unsigned();
            $table->foreign('biometric_id')->references('id')->on('biometrics')->onDelete('cascade');
            $table->string('required_working_minutes');
            $table->string('total_working_minutes');
            $table->string('undertime_minutes');
            $table->string('overtime_minutes');
            $table->string('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_time_records');
    }
};
