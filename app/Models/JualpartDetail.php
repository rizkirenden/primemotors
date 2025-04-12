<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JualpartDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jualpart_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jualpart_id',
        'kode_barang',
        'nama_part',
        'stn',
        'tipe',
        'merk',
        'jumlah',
        'harga_toko',
        'margin_persen',
        'harga_jual',
        'discount',
        'total_harga_part'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'harga_toko' => 'decimal:3',
        'margin_persen' => 'decimal:2',
        'harga_jual' => 'decimal:3',
        'discount' => 'decimal:2',
        'total_harga_part' => 'decimal:2',
    ];

    /**
     * Get the jualpart that owns the detail.
     */
    public function jualpart()
    {
        return $this->belongsTo(Jualpart::class);
    }

    /**
     * Get the sparepart associated with the detail.
     */
    public function sparepart()
    {
        return $this->belongsTo(Datasparepat::class, 'kode_barang', 'kode_barang');
    }
}
