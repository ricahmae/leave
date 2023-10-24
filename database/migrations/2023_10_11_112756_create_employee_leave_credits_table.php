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
        Schema::create('employee_leave_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_profile_id')->unsigned();
            $table->foreign('employee_profile_id')->references('id')->on('employee_profiles')->onDelete('cascade');
            $table->unsignedBigInteger('leave_type_id')->unsigned();
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade');
            $table->string('start_leave_credit');
            $table->string('total_leave_credit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_leave_credits');
    }
};
