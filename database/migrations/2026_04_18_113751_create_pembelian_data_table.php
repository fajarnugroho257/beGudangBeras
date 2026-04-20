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
        Schema::create('pembelian_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelian_id');
            $table->enum('pembayaran', ['hutang', 'cash'])->nullable();
            $table->unsignedBigInteger('barang_id');
            $table->string('pembelian_kotor', 10);
            $table->string('pembelian_potongan', 10);
            $table->string('pembelian_bersih', 10);
            $table->string('pembelian_harga', 20);
            $table->string('pembelian_total', 20);
            $table->enum('pembelian_nota_st', ['yes', 'no'])->default('no');
            $table->timestamps();
            $table->index('pembelian_id', 'pembelian_pembelian_id_foreign');
            $table->index('barang_id', 'pembelian_barang_id_foreign');
            $table->foreign('pembelian_id')->references('id')->on('pembelian')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('barang_id')->references('id')->on('barang')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_data');
    }
};
