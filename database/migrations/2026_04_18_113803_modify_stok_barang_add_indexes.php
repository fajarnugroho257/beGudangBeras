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
        Schema::table('stok_barang', function (Blueprint $table) {
            // Add indexes if they don't exist
            if (!Schema::hasIndex('stok_barang', 'stok_barang_barang_id_foreign')) {
                $table->index('barang_id', 'stok_barang_barang_id_foreign');
            }
            if (!Schema::hasIndex('stok_barang', 'stok_barang_suplier_id_foreign')) {
                $table->index('suplier_id', 'stok_barang_suplier_id_foreign');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_barang', function (Blueprint $table) {
            if (Schema::hasIndex('stok_barang', 'stok_barang_barang_id_foreign')) {
                $table->dropIndex('stok_barang_barang_id_foreign');
            }
            if (Schema::hasIndex('stok_barang', 'stok_barang_suplier_id_foreign')) {
                $table->dropIndex('stok_barang_suplier_id_foreign');
            }
        });
    }
};
