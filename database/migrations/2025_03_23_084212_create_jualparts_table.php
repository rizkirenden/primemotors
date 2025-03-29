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
        Schema::create('jualparts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama_part');
            $table->string('stn');
            $table->string('tipe');
            $table->string('merk');
            $table->date('tanggal_keluar');
            $table->date('tanggal_pembayaran')->nullable();
            $table->integer('jumlah');
            $table->decimal('harga_toko', 15, 3);
            $table->decimal('margin_persen', 5, 2)->nullable();
            $table->decimal('harga_jual', 15, 3);
            $table->decimal('discount', 5, 2);
            $table->decimal('total_harga_part', 15, 2);
            $table->enum('metode_pembayaran', ['Tunai', 'Kredit', 'Bank_Transfer'])->nullable();
            $table->string('nama_pelanggan')->nullable();
            $table->string('alamat_pelanggan')->nullable();
            $table->string('nomor_pelanggan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jualparts');
    }
};
