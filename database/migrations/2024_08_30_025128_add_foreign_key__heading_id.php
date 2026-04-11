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
        Schema::table('app_menu', function (Blueprint $table) {
            // foreign key
            $table->foreign('app_heading_id')->references('app_heading_id')->on('app_heading_menu')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_menu', function (Blueprint $table) {
            $table->dropForeign('app_heading_id');
        });
    }
};
