<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatashowroomsTable extends Migration
{
    public function up()
    {
        Schema::create('datashowrooms', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_polisi', 10);
            $table->string('merk_model', 255);
            $table->date('tahun_pembuatan');
            $table->string('nomor_rangka', 20);
            $table->string('nomor_mesin', 20);
            $table->string('bahan_bakar', 20);
            $table->integer('kapasitas_mesin');
            $table->integer('jumlah_roda');
            $table->decimal('harga', 15, 2);
            $table->date('tanggal_registrasi');
            $table->date('masa_berlaku_stnk');
            $table->date('masa_berlaku_pajak');
            $table->string('status_kepemilikan', 20);
            $table->integer('kilometer');
            $table->text('fitur_keamanan');
            $table->text('riwayat_servis');
            $table->enum('status', ['tersedia', 'terjual']);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('datashowrooms');
    }
}
