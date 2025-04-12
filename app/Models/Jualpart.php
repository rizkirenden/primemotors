<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jualpart extends Model
{
    protected $fillable = [
        'invoice_number',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'nama_pelanggan',
        'alamat_pelanggan',
        'nomor_pelanggan',
        'total_transaksi'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(JualpartItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
