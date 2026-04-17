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
            $table->string('role_menu_id', 10)->primary();
            $table->string('menu_id', 5);
            $table->string('role_id', 5);
            $table->timestamps();
            $table->unique(['menu_id', 'role_id'], 'app_role_menu_menu_id_role_id_unique');
            $table->index('role_id', 'app_role_menu_role_id_foreign');
            $table->foreign('menu_id')->references('menu_id')->on('app_menu')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('app_role')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_role_menu');
    }
};
