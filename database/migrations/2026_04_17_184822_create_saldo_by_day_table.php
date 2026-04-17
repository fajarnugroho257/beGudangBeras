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
        Schema::create('saldo_by_day', function (Blueprint $table) {
            $table->id();
            $table->string('saldo_val', 12);
            $table->string('saldo_tagihan', 12)->nullable();
            $table->string('saldo_sisa', 12)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_by_day');
    }
};
