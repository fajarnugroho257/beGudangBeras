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
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('kategori_id');
            $table->string('artikel_title');
            $table->string('artikel_slug');
            $table->text('artikel_desc');
            $table->string('artikel_views', 5);
            $table->dateTime('artikel_date');
            $table->string('artikel_path', 200)->nullable();
            $table->string('artikel_name', 200)->nullable();
            $table->timestamps();
            // foreign key
            $table->foreign('author_id')->references('id')->on('artikel_author')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kategori_id')->references('id')->on('artikel_kategori')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
