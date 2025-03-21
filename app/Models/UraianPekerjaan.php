<?php

namespace App\Models;

use Dompdf\FrameDecorator\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UraianPekerjaan extends Model
{
    use HasFactory;
    protected $table = 'uraian_pekerjaans';
    protected $fillable = [
        'jenis_pekerjaan',
        'jenis_mobil',
        'waktu_pengerjaan',
        'ongkos_pengerjaan',
    ];
}
