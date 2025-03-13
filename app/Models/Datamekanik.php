<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datamekanik extends Model
{
    use HasFactory;

    protected $table = 'datamekaniks'; // Make sure your table name matches

    // Specify the columns that are mass assignable
    protected $fillable = ['nama_mekanik', 'nomor_hp', 'alamat', 'tanggal_lahir', 'tanggal_masuk_karyawan'];
}
