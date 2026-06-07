<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $fillable = [
        'batch_id',
        'nomor_faktur',
        'id_barang',
        'nama_barang',
        'unit',
        'tanggal_keluar',
        'jumlah',
        'bukti_file',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(
            ManajemenBarang::class,
            'id_barang',
            'id_barang'
        );
    }
}