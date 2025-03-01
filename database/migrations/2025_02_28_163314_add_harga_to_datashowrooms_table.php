<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHargaToDatashowroomsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('datashowrooms', function (Blueprint $table) {
            $table->decimal('harga', 15, 2); // Menambahkan kolom harga
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datashowrooms', function (Blueprint $table) {
            $table->dropColumn('harga'); // Menghapus kolom harga jika migrasi dibatalkan
        });
    }
};
