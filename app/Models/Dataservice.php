<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataservice extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'dataservices';

    protected $fillable = [
        'no_spk',
        'costumer',
        'contact_person',
        'masuk',
        'keluar',
        'no_polisi',
        'nama_mekanik',
        'tahun',
        'tipe_mobile',
        'warna',
        'no_rangka',
        'no_mesin',
        'jenis_pekerjaan',
        'jenis_mobil',
        'waktu_pengerjaan',
        'ongkos_pengerjaan',
        'kilometer',
        'keluhan_costumer',
        'kode_barang',
        'nama_part',
        'tanggal_keluar',
        'jumlah',
        'uraian_jasa_perbaikan',
        'status',
    ];

    public function partkeluar()
    {
        return $this->hasMany(Partkeluar::class, 'dataservice_id');
    }
public function datasparepat()
{
    return $this->belongsTo(Datasparepat::class, 'kode_barang', 'kode_barang');
}
public function uraianpekerjan()
{
    return $this->belongsTo(UraianPekerjaan::class, 'jenis_pekerjaan', 'jenis_pekerjaan');
}
}
