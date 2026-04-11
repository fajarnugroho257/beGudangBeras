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
        Schema::create('operasional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengiriman_data_id');
            $table->string('ops_nama', 50)->nullable();
            $table->string('ops_jumlah', 100)->nullable();
            $table->string('ops_total', 10)->nullable();
            $table->timestamps();
            //
            $table->foreign('pengiriman_data_id')->references('id')->on('pengiriman_data')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
