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
            $table->string('menu_name', 100);
            $table->string('menu_url', 100);
            $table->string('menu_parent', 5)->default('0');
            $table->timestamps();
            // foreign key
            // $table->foreign('portal_id')->references('portal_id')->on('app_portal')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('app_menu');
    }
};
