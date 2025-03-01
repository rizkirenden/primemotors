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
        Schema::create('datasparepats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama_part');
            $table->string('stn');
            $table->string('tipe');
            $table->string('merk');
            $table->decimal('harga_toko', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->integer('jumlah')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasparepats');
    }
};
