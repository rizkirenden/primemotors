<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JualpartItem extends Model
{
    protected $table = 'jualpart_items';
    protected $fillable = [
        'jualpart_id',
        'kode_barang',
        'nama_part',
        'stn',
        'tipe',
        'merk',
        'jumlah',
        'tanggal_keluar',
        'harga_toko',
        'margin_persen',
        'harga_jual',
        'discount',
        'total_harga_part'
    ];

    public function jualpart(): BelongsTo
    {
        return $this->belongsTo(Jualpart::class);
    }

    // In App\Models\JualpartItem.php
    public function partkeluar()
    {
        return $this->hasOne(Partkeluar::class, 'kode_barang', 'kode_barang')
                   ->where('jualpart_id', $this->jualpart_id)
                   ->latest(); // Ambil record terbaru jika ada duplikat
    }
}
