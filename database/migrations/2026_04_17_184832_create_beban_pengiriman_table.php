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
        Schema::create('beban_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengiriman_id');
            $table->string('nama', 50)->nullable();
            $table->string('jumlah', 15);
            $table->timestamps();
            $table->index('pengiriman_id', 'beban_pengiriman_pengiriman_id_foreign');
            $table->foreign('pengiriman_id')->references('id')->on('pengiriman')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beban_pengiriman');
    }
};
