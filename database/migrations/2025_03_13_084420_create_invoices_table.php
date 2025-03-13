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
            $table->id(); // Auto-incrementing primary key
            $table->string('no_invoice')->unique(); // Unique invoice number
            $table->foreignId('dataservice_id')->constrained()->onDelete('cascade'); // Foreign key referencing 'dataservices' table
            $table->string('kode_barang'); // Just a string column, not a foreign key
            $table->date('tanggal_invoice'); // Invoice date
            $table->string('nama_mekanik');
            $table->string('nama_part'); // Part name
            $table->integer('jumlah'); // Quantity
            $table->decimal('harga_jual', 15, 2); // Selling price
            $table->decimal('total_harga_part', 15, 2); // Total part price
            $table->decimal('discount_part', 5, 2); // Discount
            $table->decimal('biaya_jasa', 15, 2); // Service fee
            $table->decimal('discount_biaya_jasa', 5, 2); // Discount
            $table->decimal('ppn', 5, 2); // VAT
            $table->decimal('total_harga', 15, 2); // Total price
            $table->timestamps(); // Created_at and updated_at
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
