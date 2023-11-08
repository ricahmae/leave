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
        Schema::create('mone_application_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monetization_application_id')->unsigned();
            $table->foreign('monetization_application_id')->references('id')->on('monetization_applications')->onDelete('cascade');
            $table->unsignedBigInteger('action_by')->unsigned();
            $table->foreign('action_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('action')->nullable();
            $table->string('status');
            $table->string('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mone_application_logs');
    }
};
