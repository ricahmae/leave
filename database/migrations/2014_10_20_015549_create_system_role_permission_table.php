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
        Schema::create('system_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('module');
            $table->boolean('active')->default(FALSE);
            $table->unsignedBigInteger('system_role_id')->unsigned();
            $table->foreign('system_role_id')->references('id')->on('system_roles');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE system_role_permissions AUTO_INCREMENT = 10000');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_role_permissions');
    }
};
