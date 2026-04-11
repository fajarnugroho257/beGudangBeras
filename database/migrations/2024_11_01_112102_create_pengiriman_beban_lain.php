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
        Schema::create('pengiriman_beban_lain', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengiriman_id');
            $table->string('beban_nama', 150)->nullable();
            $table->string('beban_value', 100)->nullable();
            $table->date('beban_tgl')->nullable();
            $table->timestamps();
            //
            $table->foreign('pengiriman_id')->references('id')->on(table: 'pengiriman')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_beban_lain');
    }
};
