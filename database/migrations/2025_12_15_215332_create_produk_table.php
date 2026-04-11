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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('produk_nama');
            $table->string('produk_harga', 100);
            $table->string('produk_rating', 2);
            $table->string('produk_short_desc');
            $table->text('produk_desc');
            $table->string('produk_path');
            $table->string('produk_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
