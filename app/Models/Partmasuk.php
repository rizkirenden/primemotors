<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partmasuk extends Model
{
    use HasFactory;
    protected $table = 'partmasuks';
    protected $fillable = [
        'kode_barang',
        'nama_part',
        'stn',
        'tipe',
        'merk',
        'tanggal_masuk',
        'jumlah',
    ];
}
