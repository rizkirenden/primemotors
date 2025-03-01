<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partkeluar extends Model
{
    use HasFactory;
    protected $table = 'partkeluars';
    protected $fillable = [
        'kode_barang',
        'nama_part',
        'stn',
        'tipe',
        'merk',
        'tanggal_keluar',
        'jumlah',
    ];
}
