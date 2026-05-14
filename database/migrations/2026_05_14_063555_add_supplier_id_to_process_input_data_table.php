<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('process_input_data', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')
                ->after('barang_id');

            $table->index(
                'supplier_id',
                'process_input_data_supplier_id_foreign'
            );

            $table->foreign('supplier_id')
                ->references('id')
                ->on('suplier')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('process_input_data', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropIndex('process_input_data_supplier_id_foreign');
            $table->dropColumn('supplier_id');
        });
    }
};