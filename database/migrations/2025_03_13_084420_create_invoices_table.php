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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->foreignId('dataservice_id')->constrained()->onDelete('cascade');
            $table->string('kode_barang');
            $table->date('tanggal_invoice');
            $table->string('nama_mekanik');
            $table->string('nama_part');
            $table->integer('jumlah');
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('total_harga_part', 15, 2);
            $table->decimal('discount_part', 5, 2);
            $table->string('jenis_pekerjaan');
            $table->text('ongkos_pengerjaan')->nullable();
            $table->decimal('discount_ongkos_pengerjaan', 5, 2);
            $table->decimal('total_harga_uraian_pekerjaan', 15, 2);
            $table->decimal('ppn', 5, 2); // VAT
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
