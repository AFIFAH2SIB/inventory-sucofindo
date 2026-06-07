<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class HistoryBarangController extends Controller
{
    protected array $units = [
        'smo-tank'   => 'SMO Tank',
        'fire'       => 'FIRE',
        'isps-code'  => 'ISPS CODE',
        'jip'        => 'JIP',
        'spot-order' => 'Spot ORDER',
        'aebt'       => 'AEBT',
        'industri'   => 'INDUSTRI',
        'hmpm'       => 'HMPM',
    ];

    public function index(string $unit)
    {
        $unitName    = $this->units[$unit] ?? strtoupper($unit);
        $barangKeluar = BarangKeluar::where('unit', $unitName)
                            ->orderBy('tanggal_keluar', 'desc')
                            ->get();

        return view('history-barang.index', compact('unit', 'unitName', 'barangKeluar'));
    }
}
