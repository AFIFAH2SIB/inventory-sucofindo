<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\ManajemenBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BarangKeluarController extends Controller
{
    protected array $units = [
        'SMO Tank',
        'FIRE',
        'ISPS CODE',
        'JIP',
        'Spot ORDER',
        'AEBT',
        'INDUSTRI',
        'HMPM',
    ];

    public function index()
    {
        $units        = $this->units;
        $ManajemenBarang = ManajemenBarang::orderBy('id_barang')->get();

        // Hitung stok real-time per id_barang
        $stokData = DB::table('barang_masuk')
            ->select(
                'barang_masuk.id_barang',
                DB::raw('SUM(barang_masuk.jumlah) - COALESCE(SUM(bk.jumlah), 0) as stok')
            )
            ->leftJoin(
                DB::raw('(SELECT id_barang, SUM(jumlah) as jumlah FROM barang_keluar GROUP BY id_barang) as bk'),
                'barang_masuk.id_barang', '=', 'bk.id_barang'
            )
            ->whereNotNull('barang_masuk.id_barang')
            ->groupBy('barang_masuk.id_barang')
            ->get()
            ->keyBy('id_barang')
            ->map(fn($row) => $row->stok);

        // Kelompokkan per batch_id (record lama tanpa batch_id tampil individual)
        $all = BarangKeluar::orderBy('tanggal_keluar', 'desc')->orderBy('id')->get();
        $barangKeluar = $all
            ->groupBy(fn($item) => $item->batch_id ?? 'solo_' . $item->id)
            ->map(function ($items) {
                $first = $items->first();
                return (object) [
                    'id'             => $first->id,
                    'batch_id'       => $first->batch_id,
                    'nomor_faktur'   => $first->nomor_faktur,
                    'tanggal_keluar' => $first->tanggal_keluar,
                    'bukti_file'     => $first->bukti_file,
                    'jumlah'    => $items->count(),
                    'detail_items'   => $items->map(fn($i) => (object) [
                        'id_barang'   => $i->id_barang,
                        'nama_barang' => $i->nama_barang,
                        'unit'        => $i->unit,
                        'jumlah'      => $i->jumlah,
                    ])->values(),
                ];
            })
            ->values();

        return view('barang-keluar.index', compact('barangKeluar', 'units', 'ManajemenBarang', 'stokData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_faktur'          => 'required|string|max:100',
            'tanggal_keluar'        => 'required|date',
            'bukti_file'            => 'nullable|file|mimes:pdf|max:5120',
            'items'                 => 'required|array|min:1',
            'items.*.id_barang'     => 'required|string|max:50',
            'items.*.nama_barang'   => 'required|string|max:255',
            'items.*.unit'          => 'required|string|max:100',
            'items.*.jumlah'        => 'required|integer|min:1',
        ], [
            'nomor_faktur.required'         => 'Nomor Faktur wajib diisi.',
            'tanggal_keluar.required'       => 'Tanggal Keluar wajib diisi.',
            'items.required'                => 'Minimal satu barang harus diisi.',
            'items.*.id_barang.required'    => 'ID Barang wajib dipilih.',
            'items.*.unit.required'         => 'Unit wajib dipilih.',
            'items.*.jumlah.required'       => 'Jumlah wajib diisi.',
            'items.*.jumlah.min'            => 'Jumlah minimal 1.',
            'bukti_file.mimes'              => 'Bukti file harus berformat PDF.',
            'bukti_file.max'                => 'Ukuran file maksimal 5 MB.',
        ]);

        $batchId   = Str::uuid()->toString();
        $buktiFile = null;

        if ($request->hasFile('bukti_file')) {
            $originalName = $request->file('bukti_file')->getClientOriginalName();
            $request->file('bukti_file')->storeAs('bukti-keluar', $originalName, 'public');
            $buktiFile = 'bukti-keluar/' . $originalName;
        }

        foreach ($request->items as $item) {
            BarangKeluar::create([
                'batch_id'       => $batchId,
                'nomor_faktur'   => $request->nomor_faktur,
                'id_barang'      => $item['id_barang'],
                'nama_barang'    => $item['nama_barang'],
                'unit'           => $item['unit'],
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah'         => $item['jumlah'],
                'bukti_file'     => $buktiFile,
            ]);
        }

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil ditambahkan.');
    }

    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        $request->validate([
            'nomor_faktur'          => 'required|string|max:100',
            'tanggal_keluar'        => 'required|date',
            'bukti_file'            => 'nullable|file|mimes:pdf|max:5120',
            'items'                 => 'required|array|min:1',
            'items.*.id_barang'     => 'required|string|max:50',
            'items.*.nama_barang'   => 'required|string|max:255',
            'items.*.unit'          => 'required|string|max:100',
            'items.*.jumlah'        => 'required|integer|min:1',
        ], [
            'nomor_faktur.required'         => 'Nomor Faktur wajib diisi.',
            'tanggal_keluar.required'       => 'Tanggal Keluar wajib diisi.',
            'items.required'                => 'Minimal satu barang harus diisi.',
            'items.*.id_barang.required'    => 'ID Barang wajib dipilih.',
            'items.*.unit.required'         => 'Unit wajib dipilih.',
            'items.*.jumlah.required'       => 'Jumlah wajib diisi.',
            'items.*.jumlah.min'            => 'Jumlah minimal 1.',
            'bukti_file.mimes'              => 'Bukti file harus berformat PDF.',
            'bukti_file.max'                => 'Ukuran file maksimal 5 MB.',
        ]);

        $batchId      = $barangKeluar->batch_id;
        $oldBuktiFile = $barangKeluar->bukti_file;

        // Hapus semua record lama dalam batch ini
        if ($batchId) {
            BarangKeluar::where('batch_id', $batchId)->delete();
        } else {
            $barangKeluar->delete();
            $batchId = Str::uuid()->toString();
        }

        // Proses file baru (simpan dengan nama asli)
        $buktiFile = $oldBuktiFile;
        if ($request->hasFile('bukti_file')) {
            if ($oldBuktiFile) {
                Storage::disk('public')->delete($oldBuktiFile);
            }
            $originalName = $request->file('bukti_file')->getClientOriginalName();
            $request->file('bukti_file')->storeAs('bukti-keluar', $originalName, 'public');
            $buktiFile = 'bukti-keluar/' . $originalName;
        }

        // Re-create semua item
        foreach ($request->items as $item) {
            BarangKeluar::create([
                'batch_id'       => $batchId,
                'nomor_faktur'   => $request->nomor_faktur,
                'id_barang'      => $item['id_barang'],
                'nama_barang'    => $item['nama_barang'],
                'unit'           => $item['unit'],
                'tanggal_keluar' => $request->tanggal_keluar,
                'jumlah'         => $item['jumlah'],
                'bukti_file'     => $buktiFile,
            ]);
        }

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil diperbarui.');
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        $batchId = $barangKeluar->batch_id;

        if ($batchId) {
            // Hapus file bukti jika ada
            $one = BarangKeluar::where('batch_id', $batchId)->whereNotNull('bukti_file')->first();
            if ($one?->bukti_file) {
                Storage::disk('public')->delete($one->bukti_file);
            }
            BarangKeluar::where('batch_id', $batchId)->delete();
        } else {
            if ($barangKeluar->bukti_file) {
                Storage::disk('public')->delete($barangKeluar->bukti_file);
            }
            $barangKeluar->delete();
        }

        return redirect()->route('barang-keluar.index')->with('success', 'Barang keluar berhasil dihapus.');
    }
}
