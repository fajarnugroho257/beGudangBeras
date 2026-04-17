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
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id', 5)->primary();
            $table->string('name', 255)->nullable();
            $table->string('role_id', 5);
            $table->string('username', 100);
            $table->string('password', 255);
            $table->timestamps();
            $table->index('role_id', 'users_role_id_foreign');
            $table->foreign('role_id')->references('role_id')->on('app_role')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
