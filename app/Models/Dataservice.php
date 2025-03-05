<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataservice extends Model
{
    use HasFactory;
    protected $table = 'dataservices';

    protected $fillable = [
        'no_spk',
        'tanggal',
        'costumer',
        'contact_person',
        'masuk',
        'keluar',
        'no_polisi',
        'nama_mekanik',
        'tahun',
        'tipe',
        'warna',
        'no_rangka',
        'no_mesin',
        'keluhan_costumer',
        'kode_barang',
        'nama_part',
        'tanggal_keluar',
        'jumlah',
        'uraian_pekerjaan',
        'uraian_jasa_perbaikan',
        'status',
    ];

    public function partkeluar()
    {
        return $this->hasMany(Partkeluar::class, 'kode_barang', 'kode_barang');
    }
}
