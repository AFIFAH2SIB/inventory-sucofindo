<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManajemenBarang;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanStokController extends Controller
{
    public function index(Request $request)
    {
        $query = ManajemenBarang::query();

        $stokBarang = $query->get()->map(function ($barang) {

            $masuk = DB::table('barang_masuk')
                ->where('id_barang', $barang->id_barang)
                ->sum('jumlah');

            $keluar = DB::table('barang_keluar')
                ->where('id_barang', $barang->id_barang)
                ->sum('jumlah');

            $stok = $masuk - $keluar;

            $minimum = $barang->batas_minimum ?? 0;

            if ($stok <= 0) {
                $status = 'Habis';
            } elseif ($stok <= $minimum) {
                $status = 'Perlu Pengadaan';
            } else {
                $status = 'Aman';
            }

            return (object)[
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'stok' => $stok,
                'status' => $status
            ];
        });

        return view(
            'laporan-stok.index',
            compact('stokBarang')
        );
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->filter;

        $stokBarang = ManajemenBarang::all()->map(function ($barang) {

            $masuk = DB::table('barang_masuk')
                ->where('id_barang', $barang->id_barang)
                ->sum('jumlah');

            $keluar = DB::table('barang_keluar')
                ->where('id_barang', $barang->id_barang)
                ->sum('jumlah');

            $stok = $masuk - $keluar;

            $minimum = $barang->safety_stock ?? 0;

            if ($stok <= 0) {
                $status = 'Habis';
            } elseif ($stok <= $minimum) {
                $status = 'Perlu Pengadaan';
            } else {
                $status = 'Aman';
            }

            return (object)[
                'id_barang'   => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'stok'        => max(0, $stok),
                'status'      => $status,
            ];
        });

        if ($filter === 'Minimum') {

            $stokBarang = $stokBarang->filter(function ($item) {

                return $item->status === 'Perlu Pengadaan'
                    || $item->status === 'Habis';
            });
        }

        $pdf = Pdf::loadView(
            'laporan-stok.pdf',
            compact('stokBarang')
        );

        return $pdf->download(
            'laporan-stok.pdf'
        );
    }
}