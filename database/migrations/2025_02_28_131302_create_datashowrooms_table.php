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
        Schema::create('datashowrooms', function (Blueprint $table) {
            $table->id();
        $table->string('nomor_polisi');
        $table->string('merk_model');
        $table->year('tahun_pembuatan');
        $table->string('nomor_rangka');
        $table->string('nomor_mesin');
        $table->string('bahan_bakar');
        $table->integer('kapasitas_mesin');
        $table->integer('jumlah_roda');
        $table->date('tanggal_registrasi');
        $table->date('masa_berlaku_stnk');
        $table->date('masa_berlaku_pajak');
        $table->string('status_kepemilikan');
        $table->integer('kilometer');
        $table->string('fitur_keamanan');
        $table->string('riwayat_servis');
        $table->enum('status', ['terjual', 'tersedia'])->default('tersedia');
        $table->string('foto')->nullable();
        $table->decimal('harga', 15, 2);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datashowrooms');
    }
};
