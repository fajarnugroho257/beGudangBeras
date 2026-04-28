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
        Schema::table('nota_data', function (Blueprint $table) {
            $table->unsignedBigInteger('pembelian_id')->after('suplier_id');
            $table->foreign('pembelian_id')
                ->references('id')
                ->on('pembelian')
                ->onDelete('cascade');
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
