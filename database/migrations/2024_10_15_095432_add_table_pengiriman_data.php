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
            $table->string('data_merek', 50)->nullable();
            $table->string('data_barang', 100)->nullable();
            $table->integer('data_box', false)->nullable();
            $table->string('data_box_rupiah', 100)->nullable();
            $table->string('data_tonase', 10)->nullable();
            $table->string('data_estimasi', 100)->nullable();
            $table->string('data_datas', 100)->nullable();
            $table->string('data_harga', 15)->nullable();
            $table->string('data_total', 15)->nullable();
            $table->enum('data_st', ['yes', 'no'])->default('no');
            $table->timestamps();
            //
            $table->foreign('pengiriman_id')->references('id')->on('pengiriman')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->drop();
        });
    }
};
