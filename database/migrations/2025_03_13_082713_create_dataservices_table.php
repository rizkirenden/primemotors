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
                $table->string('costumer');
                $table->string('contact_person');
                $table->datetime('masuk');
                $table->datetime('keluar')->nullable();
                $table->string('no_polisi');
                $table->string('nama_mekanik')->nullable();
                $table->integer('tahun');
                $table->string('tipe_mobile');
                $table->string('warna');
                $table->string('no_rangka');
                $table->string('no_mesin');
                $table->decimal('kilometer', 15, 2);
                $table->text('keluhan_costumer');
                $table->string('kode_barang')->nullable();
                $table->string('nama_part')->nullable();
                $table->string('stn')->nullable();
                $table->string('tipe')->nullable();
                $table->string('merk')->nullable();
                $table->date('tanggal_keluar')->nullable();
                $table->integer('jumlah')->nullable();
                $table->text('uraian_pekerjaan')->nullable();
                $table->text('uraian_jasa_perbaikan')->nullable();
                $table->enum('status', ['menunggu', 'pulang'])->default('menunggu');
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
