<?php

namespace App\Http\Controllers;

use App\Models\ManajemenBarang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total jenis barang
        $totalBarang = ManajemenBarang::count();

        // Total transaksi barang masuk
        $totalMasuk = BarangMasuk::distinct('nomor_faktur')
            ->count('nomor_faktur');

        // Total transaksi barang keluar
        $totalKeluar = BarangKeluar::distinct('nomor_faktur')
            ->count('nomor_faktur');

        // Data stok per barang
        $stokList = DB::table('manajemen_barang as mb')
            ->leftJoin(
                DB::raw('(
                    SELECT id_barang, SUM(jumlah) as total_masuk
                    FROM barang_masuk
                    GROUP BY id_barang
                ) bm'),
                'mb.id_barang',
                '=',
                'bm.id_barang'
            )
            ->leftJoin(
                DB::raw('(
                    SELECT id_barang, SUM(jumlah) as total_keluar
                    FROM barang_keluar
                    GROUP BY id_barang
                ) bk'),
                'mb.id_barang',
                '=',
                'bk.id_barang'
            )
            ->select(
                'mb.id_barang',
                'mb.nama_barang',
                DB::raw('COALESCE(bm.total_masuk,0) as masuk'),
                DB::raw('COALESCE(bk.total_keluar,0) as keluar'),
                DB::raw('COALESCE(bm.total_masuk,0) - COALESCE(bk.total_keluar,0) as stok')
            )
            ->orderBy('mb.id_barang')
            ->get();

        // Barang yang stoknya minimum / habis
        $stokMinimum = $stokList->filter(function ($item) {
            return $item->stok <= 5;
        });

        return view('dashboard.index', compact(
            'totalBarang',
            'totalMasuk',
            'totalKeluar',
            'stokList',
            'stokMinimum'
        ));
    }
}