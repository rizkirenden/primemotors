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
}
