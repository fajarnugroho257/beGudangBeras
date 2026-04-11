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
        Schema::create('app_role_menu', function (Blueprint $table) {
            $table->string('role_menu_id', 5)->primary();
            $table->string('user_id', 5);
            $table->string('menu_id', 5);
            $table->string('role_id', 5);
            $table->string('role_menu_url', 100);
            $table->string('role_menu_parent', 5)->default('0');
            $table->timestamps();
            // foreign key
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('menu_id')->references('menu_id')->on('app_menu')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('app_role')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
