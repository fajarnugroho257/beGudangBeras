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
        Schema::table('suplier', function (Blueprint $table) {
            if (Schema::hasColumn('suplier', 'suplier_tgl')) {
                $table->dropColumn('suplier_tgl');
            }
            if (Schema::hasColumn('suplier', 'suplier_nota_st')) {
                $table->dropColumn('suplier_nota_st');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suplier', function (Blueprint $table) {
            $table->date('suplier_tgl')->nullable();
            $table->enum('suplier_nota_st', ['yes', 'no'])->default('no');
        });
    }
};
