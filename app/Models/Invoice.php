<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $fillable = [
        'no_invoice',
        'dataservice_id',
        'kode_barang',
        'tanggal_invoice',
        'nama_mekanik',
        'nama_part',
        'jumlah',
        'harga_jual',
        'total_harga_part',
        'discount_part',
        'jenis_pekerjaan',
        'ongkos_pengerjaan',
        'discount_ongkos_pengerjaan',
        'total_harga_uraian_pekerjaan',
        'ppn',
        'total_harga',

    ];
    public function dataservice()
    {
        return $this->belongsTo(Dataservice::class, 'dataservice_id');
    }
    public function partkeluar()
    {
        return $this->hasMany(Partkeluar::class, 'dataservice_id');
    }
    public function datasparepat()
    {
        return $this->hasOne(Datasparepat::class, 'kode_barang', 'kode_barang');
    }
    public function mekanik()
    {
        return $this->belongsTo(Datamekanik::class, 'nama_mekanik', 'nama_mekanik');
    }
}
