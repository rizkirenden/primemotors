<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datasparepat extends Model
{
    use HasFactory;
    protected $table = 'datasparepats';
    protected $fillable = [
        'kode_barang',
        'nama_part',
        'stn',
        'tipe',
        'merk',
        'harga_toko',
        'margin_persen',
        'harga_jual',
        'jumlah',
    ];
}
