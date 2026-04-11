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
        Schema::create('produk_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produk_id');
            $table->enum('detail_jenis', ['desc', 'detail'])->default('desc');
            $table->string('detail_title')->nullable();
            $table->string('detail_desc')->nullable();
            $table->timestamps();
            // foreign key
            $table->foreign('produk_id')->references('id')->on('produk')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_detail');
    }
};
