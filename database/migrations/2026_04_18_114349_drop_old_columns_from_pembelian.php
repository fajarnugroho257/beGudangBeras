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
            // Drop old columns that have been moved to pembelian_data
            if (Schema::hasColumn('pembelian', 'pembayaran')) {
                $table->dropColumn('pembayaran');
            }
            if (Schema::hasColumn('pembelian', 'barang_id')) {
                try {
                    $table->dropForeign(['barang_id']);
                } catch (Exception $e) {}
                $table->dropColumn('barang_id');
            }
            if (Schema::hasColumn('pembelian', 'pembelian_kotor')) {
                $table->dropColumn('pembelian_kotor');
            }
            if (Schema::hasColumn('pembelian', 'pembelian_potongan')) {
                $table->dropColumn('pembelian_potongan');
            }
            if (Schema::hasColumn('pembelian', 'pembelian_bersih')) {
                $table->dropColumn('pembelian_bersih');
            }
            if (Schema::hasColumn('pembelian', 'pembelian_harga')) {
                $table->dropColumn('pembelian_harga');
            }
            if (Schema::hasColumn('pembelian', 'pembelian_total')) {
                $table->dropColumn('pembelian_total');
            }
            if (Schema::hasColumn('pembelian', 'pembelian_nota_st')) {
                $table->dropColumn('pembelian_nota_st');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelian', 'pembayaran')) {
                $table->enum('pembayaran', ['hutang', 'cash'])->nullable();
            }
            if (!Schema::hasColumn('pembelian', 'barang_id')) {
                $table->unsignedBigInteger('barang_id')->nullable();
            }
            if (!Schema::hasColumn('pembelian', 'pembelian_kotor')) {
                $table->string('pembelian_kotor', 10)->nullable();
            }
            if (!Schema::hasColumn('pembelian', 'pembelian_potongan')) {
                $table->string('pembelian_potongan', 10)->nullable();
            }
            if (!Schema::hasColumn('pembelian', 'pembelian_bersih')) {
                $table->string('pembelian_bersih', 10)->nullable();
            }
            if (!Schema::hasColumn('pembelian', 'pembelian_harga')) {
                $table->string('pembelian_harga', 20)->nullable();
            }
            if (!Schema::hasColumn('pembelian', 'pembelian_total')) {
                $table->string('pembelian_total', 20)->nullable();
            }
            if (!Schema::hasColumn('pembelian', 'pembelian_nota_st')) {
                $table->enum('pembelian_nota_st', ['yes', 'no'])->default('no');
            }
        });
    }
};
