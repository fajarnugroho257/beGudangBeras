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
        Schema::create('app_menu', function (Blueprint $table) {
            $table->string('menu_id', 5)->primary();
            $table->string('app_heading_id', 100)->nullable();
            $table->string('menu_name', 100);
            $table->string('menu_url', 100);
            $table->string('menu_parent', 5)->default('0');
            $table->timestamps();
            $table->index('app_heading_id', 'app_menu_app_heading_id_foreign');
            $table->foreign('app_heading_id')->references('app_heading_id')->on('app_heading_menu')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_menu');
    }
};
