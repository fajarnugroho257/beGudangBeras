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
        Schema::create('pengiriman_beban_karyawan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengiriman_id');
            $table->unsignedBigInteger('karyawan_id');
            $table->string('beban_value', 15)->nullable();
            $table->enum('beban_st', ['yes', 'no'])->default('no');
            $table->date('beban_tgl')->nullable();
            $table->timestamps();
            $table->index('pengiriman_id', 'pengiriman_beban_karyawan_pengiriman_id_foreign');
            $table->index('karyawan_id', 'pengiriman_beban_karyawan_karyawan_id_foreign');
            $table->foreign('pengiriman_id')->references('id')->on('pengiriman')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_beban_karyawan');
    }
};
