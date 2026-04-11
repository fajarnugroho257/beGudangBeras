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
        Schema::table('app_heading_menu', function (Blueprint $table) {
            $table->string('app_heading_icon')->nullable()->after('app_heading_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_heading_menu', function (Blueprint $table) {
            $table->dropColumn('app_heading_icon');
        });
    }
};
