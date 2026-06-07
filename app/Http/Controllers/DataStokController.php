<?php

namespace App\Http\Controllers;

use App\Models\ManajemenBarang;
use Illuminate\Support\Facades\DB;

class DataStokController extends Controller
{
    public function index()
    {
        $stokBarang = ManajemenBarang::all()->map(function ($barang) {

            /*
            |--------------------------------------------------------------------------
            | Stok Saat Ini
            |--------------------------------------------------------------------------
            */

            $totalMasuk = DB::table('barang_masuk')
                ->where('id_barang', $barang->id_barang)
                ->sum('jumlah');

            $totalKeluar = DB::table('barang_keluar')
                ->where('id_barang', $barang->id_barang)
                ->sum('jumlah');

            $stokSaatIni = $totalMasuk - $totalKeluar;

            /*
            |--------------------------------------------------------------------------
            | Pemakaian Harian Rata-rata
            |--------------------------------------------------------------------------
            */

            $barangKeluar = DB::table('barang_keluar')
                ->where('id_barang', $barang->id_barang)
                ->get();

            $totalPemakaian = $barangKeluar->sum('jumlah');

            $jumlahHari = $barangKeluar
                ->pluck('tanggal_keluar')
                ->unique()
                ->count();

            $avgDailyUsage = $jumlahHari > 0
                ? ($totalPemakaian / $jumlahHari)
                : 0;

            /*
            |--------------------------------------------------------------------------
            | Hitung ROP
            |--------------------------------------------------------------------------
            */

            $leadTime = $barang->lead_time ?? 0;

            $safetyStock = $barang->safety_stock ?? 0;

            $rop = ceil(
                ($avgDailyUsage * $leadTime)
                + $safetyStock
            );

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            if ($stokSaatIni <= 0) {

                $status = 'Habis';

            } elseif ($stokSaatIni <= $rop) {

                $status = 'Perlu Pengadaan';

            } else {

                $status = 'Aman';
            }

            return (object) [

                'id_barang'      => $barang->id_barang,

                'nama_barang'    => $barang->nama_barang,

                'stok'           => max(0, $stokSaatIni),

                // ditampilkan ke user sebagai Stok Minimum
                'stok_minimum'   => $rop,

                'status'         => $status,

                // hanya untuk kebutuhan internal
                'lead_time'      => $leadTime,

                'safety_stock'   => $safetyStock,

                'avg_daily'      => round($avgDailyUsage, 2),

                'rop'            => $rop,
            ];
        });

        return view(
            'data-stok.index',
            compact('stokBarang')
        );
    }
}