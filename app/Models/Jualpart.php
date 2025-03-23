<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jualpart extends Model
{
    use HasFactory;
    protected $table = 'jualparts';
    protected $fillable = [
        'kode_barang',
        'nama_part',
        'stn',
        'tipe',
        'merk',
        'tanggal_keluar',
        'tanggal_pembayaran',
        'jumlah',
        'harga_toko',
        'harga_jual',
        'margin_persen',
        'discount',
        'status',
        'total_harga_part',
        'metode_pembayaran',
        'nama_pelanggan',
        'alamat_pelanggan',
        'nomor_pelanggan',
    ];
    public function partkeluar()
    {
        return $this->hasMany(Partkeluar::class, 'jualpart_id');
    }
}
