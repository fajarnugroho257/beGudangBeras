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
        Schema::create('artikel_author', function (Blueprint $table) {
            $table->id();
            $table->string('author_name', 100);
            $table->string('author_desc')->nullable();
            $table->string('author_path')->nullable();
            $table->string('author_image', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel_author');
    }
};
