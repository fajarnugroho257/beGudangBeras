<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->after('barang_id');

            $table->index('supplier_id');

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suplier')
                ->onUpdate('restrict')
                ->onDelete('restrict'); // or 'restrict' depending on your business rule
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman_data', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
};