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
        Schema::create('uraian_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pekerjaan');
            $table->string('jenis_mobil');
            $table->integer('waktu_pengerjaan');
            $table->decimal('ongkos_pengerjaan', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uraian_pekerjaans');
    }
};
