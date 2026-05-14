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
        Schema::create('process_input', function (Blueprint $table) {
            $table->id();
            $table->date('process_input_tgl');
            $table->string('operasional', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('process_input_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_input_id');
            $table->unsignedBigInteger('barang_id');
            $table->string('tonase', 10);
            $table->timestamps();

            $table->index('process_input_id', 'process_input_id_foreign');
            $table->index('barang_id', 'process_input_data_barang_id_foreign');

            $table->foreign('process_input_id')
                ->references('id')
                ->on('process_input')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('barang_id')
                ->references('id')
                ->on('barang')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('process_output', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_input_id');
            $table->date('process_output_tgl');
            $table->timestamps();

            $table->index(
                'process_input_id',
                'process_output_process_input_id_foreign'
            );

            $table->foreign('process_input_id')
                ->references('id')
                ->on('process_input')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Schema::create('process_output_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_output_id');
            $table->unsignedBigInteger('barang_id');
            $table->string('tonase', 10);
            $table->timestamps();

            $table->index(
                'process_output_id',
                'process_output_data_process_output_id_foreign'
            );

            $table->index(
                'barang_id',
                'process_output_data_barang_id_foreign'
            );

            $table->foreign('process_output_id')
                ->references('id')
                ->on('process_output')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('barang_id')
                ->references('id')
                ->on('barang')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_output_data');
        Schema::dropIfExists('process_output');
        Schema::dropIfExists('process_input_data');
        Schema::dropIfExists('process_input');
    }
};