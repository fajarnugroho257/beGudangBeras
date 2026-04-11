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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suplier_id');
            $table->enum('pembayaran', ['hutang', 'cash'])->nullable();
            $table->string('pembelian_nama', 100);
            $table->string('pembelian_kotor', 10);
            $table->string('pembelian_potongan', 10);
            $table->string('pembelian_bersih', 10);
            $table->string('pembelian_harga', 20);
            $table->string('pembelian_total', 20);
            $table->enum('pembelian_nota_st', ['yes', 'no'])->default('no');
            $table->timestamps();
            // foreign key
            $table->foreign('suplier_id')->references('id')->on('suplier')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('pembelian');
    }
};
