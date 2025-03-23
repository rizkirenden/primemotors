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
        Schema::create('partkeluars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dataservice_id')->nullable();
            $table->unsignedBigInteger('jualpart_id')->nullable();
            $table->string('kode_barang');
            $table->string('nama_part');
            $table->string('stn');
            $table->string('tipe');
            $table->string('merk');
            $table->date('tanggal_keluar');
            $table->integer('jumlah');
            $table->text('uraian_jasa_perbaikan')->nullable();
            $table->decimal('harga_jasa_perbaikan', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->foreign('dataservice_id')->references('id')->on('dataservices')->onDelete('cascade');
            $table->foreign('jualpart_id')->references('id')->on('jualparts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partkeluars');
    }
};
