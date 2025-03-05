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
        Schema::create('dataservices', function (Blueprint $table) {
            $table->id();
            $table->string('no_spk');
            $table->date('tanggal');
            $table->string('costumer');
            $table->string('contact_person');
            $table->date('masuk');
            $table->date('keluar')->nullable();
            $table->string('no_polisi');
            $table->string('nama_mekanik');
            $table->integer('tahun');
            $table->string('tipe');
            $table->string('warna');
            $table->string('no_rangka');
            $table->string('no_mesin');
            $table->text('keluhan_costumer');
            $table->string('kode_barang')->nullable();
            $table->string('nama_part')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->integer('jumlah')->nullable();
            $table->text('uraian_pekerjaan');
            $table->text('uraian_jasa_perbaikan');
            $table->enum('status', ['menunggu', 'sedang pengerjaan', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataservices');
    }
};
