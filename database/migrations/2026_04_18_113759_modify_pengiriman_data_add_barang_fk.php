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
        Schema::table('pengiriman_data', function (Blueprint $table) {
            // Add barang_id if it doesn't exist
            if (!Schema::hasColumn('pengiriman_data', 'barang_id')) {
                $table->unsignedBigInteger('barang_id')->after('pengiriman_id');
                $table->index('barang_id', 'pengiriman_data_barang_id_foreign');
                $table->foreign('barang_id')->references('id')->on('barang')->onUpdate('cascade')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            if (Schema::hasColumn('pengiriman_data', 'barang_id')) {
                try {
                    $table->dropForeign(['barang_id']);
                } catch (Exception $e) {
                    // Foreign key might not exist
                }
                $table->dropColumn('barang_id');
            }
        });
    }
};
