<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

    protected $fillable = [
        'nomor_faktur',
        'id_barang',
        'nama_barang',
        'tanggal_masuk',
        'jumlah',
        'bukti_file'
    ];
}