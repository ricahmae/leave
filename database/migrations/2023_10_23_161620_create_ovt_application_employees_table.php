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
        Schema::create('ovt_application_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('overtime_application_id')->unsigned();
            $table->foreign('overtime_application_id')->references('id')->on('overtime_applications')->onDelete('cascade');
            $table->unsignedBigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employee_profiles')->onDelete('cascade');
            $table->string('remarks')->nullable(); 
            $table->string('date')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ovt_application_employees');
    }
};
