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
        Schema::create('pengiriman_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengiriman_id');
            $table->unsignedBigInteger('barang_id');
            $table->string('data_tonase', 10)->nullable();
            $table->string('data_harga', 15)->nullable();
            $table->string('data_total', 15)->nullable();
            $table->enum('pembayaran_st', ['yes', 'no'])->default('no');
            $table->timestamps();
            $table->index('pengiriman_id', 'pengiriman_data_pengiriman_id_foreign');
            $table->foreign('pengiriman_id')->references('id')->on('pengiriman')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_data');
    }
};
