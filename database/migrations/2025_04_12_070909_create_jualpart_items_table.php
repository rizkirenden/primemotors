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
        Schema::create('jualpart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jualpart_id')->constrained()->onDelete('cascade');
            $table->string('kode_barang');
            $table->string('nama_part');
            $table->string('stn');
            $table->string('tipe');
            $table->string('merk');
            $table->integer('jumlah');
            $table->date('tanggal_keluar');
            $table->decimal('harga_toko', 15, 3);
            $table->decimal('margin_persen', 5, 2)->nullable();
            $table->decimal('harga_jual', 15, 3);
            $table->decimal('discount', 5, 2);
            $table->decimal('total_harga_part', 15, 2);
            $table->timestamps();

            $table->index('kode_barang');
            $table->index('nama_part');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jualpart_items');
    }

};
