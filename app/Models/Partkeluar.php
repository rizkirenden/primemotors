<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partkeluar extends Model
{
    use HasFactory;
    protected $table = 'partkeluars';
    protected $fillable = [
        'dataservice_id',
        'jualpart_id',
        'kode_barang',
        'nama_part',
        'stn',
        'tipe',
        'merk',
        'tanggal_keluar',
        'status',
        'jumlah',
        'uraian_jasa_perbaikan',
        'harga_jasa_perbaikan',
    ];
    public function dataservice()
    {
        return $this->belongsTo(Dataservice::class, 'dataservice_id');
    }
    public function datasparepat()
    {
        return $this->belongsTo(Datasparepat::class, 'kode_barang', 'kode_barang');
    }
    public function jualpart()
    {
        return $this->belongsTo(Dataservice::class, 'jualpart_id');
    }
}
