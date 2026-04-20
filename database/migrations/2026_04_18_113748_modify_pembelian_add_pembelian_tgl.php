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
        Schema::table('pembelian', function (Blueprint $table) {
            // Add pembelian_tgl if it doesn't exist
            if (!Schema::hasColumn('pembelian', 'pembelian_tgl')) {
                $table->date('pembelian_tgl')->nullable()->after('suplier_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            if (Schema::hasColumn('pembelian', 'pembelian_tgl')) {
                $table->dropColumn('pembelian_tgl');
            }
        });
    }
};
