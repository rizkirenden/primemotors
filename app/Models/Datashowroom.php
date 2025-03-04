<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datashowroom extends Model
{
    use HasFactory;
    protected $table = 'datashowrooms';
    protected $fillable = [
        'nomor_polisi',
        'merk_model',
        'tahun_pembuatan',
        'nomor_rangka',
        'nomor_mesin',
        'bahan_bakar',
        'kapasitas_mesin',
        'jumlah_roda',
        'harga',
        'tanggal_registrasi',
        'masa_berlaku_stnk',
        'masa_berlaku_pajak',
        'status_kepemilikan',
        'kilometer',
        'fitur_keamanan',
        'riwayat_servis',
        'status',
        'foto',
    ];
}
