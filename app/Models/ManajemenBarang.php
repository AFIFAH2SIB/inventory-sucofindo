<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManajemenBarang extends Model
{
    protected $table = 'manajemen_barang';

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'lead_time',
        'safety_stock',
    ];

    public function barangKeluar()
    {
        return $this->hasMany(
            BarangKeluar::class,
            'id_barang',
            'id_barang'
        );
    }
}