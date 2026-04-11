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
        Schema::create('nota_bayar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nota_id');
            $table->string('bayar_value', 50);
            $table->timestamps();
            // foreign key
            $table->foreign('nota_id')->references('id')->on('nota')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_bayar');
    }
};
