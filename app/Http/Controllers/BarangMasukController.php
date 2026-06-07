<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\ManajemenBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangMasukController extends Controller
{
    public function index()
    {
        $all = BarangMasuk::orderBy('tanggal_masuk', 'desc')->orderBy('nomor_faktur')->get();
        $ManajemenBarang = ManajemenBarang::orderBy('id_barang')->get();

        // Kelompokkan per nomor_faktur untuk tampilan tabel
        $barangMasuk = $all->groupBy('nomor_faktur')->map(function ($items) {
            $first = $items->first();
            return (object) [
                'id'            => $first->id,
                'nomor_faktur'  => $first->nomor_faktur,
                'tanggal_masuk' => $first->tanggal_masuk,
                'bukti_file'    => $first->bukti_file,
                'jumlah'   => $items->count(),
                'id_barang'     => $first->id_barang ?? '',
                'nama_barang'   => $first->nama_barang ?? '',
                'detail_items'  => $items->map(fn ($i) => (object) [
                    'id_barang'   => $i->id_barang,
                    'nama_barang' => $i->nama_barang,
                    'jumlah'      => $i->jumlah,
                ])->values(),
            ];
        })->values();

        return view('barang-masuk.index', compact('barangMasuk', 'ManajemenBarang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_faktur'              => 'required|string|max:100',
            'tanggal_masuk'             => 'required|date',
            'bukti_file'                => 'nullable|file|mimes:pdf|max:5120',
            'items'                     => 'required|array|min:1',
            'items.*.id_barang'         => 'required|string|max:50',
            'items.*.nama_barang'       => 'required|string|max:255',
            'items.*.jumlah_barang'     => 'required|integer|min:1',
        ], [
            'nomor_faktur.required'         => 'Nomor Faktur wajib diisi.',
            'tanggal_masuk.required'        => 'Tanggal Masuk wajib diisi.',
            'bukti_file.mimes'              => 'Bukti File harus berupa PDF.',
            'bukti_file.max'                => 'Ukuran file maksimal 5 MB.',
            'items.required'                => 'Minimal satu barang harus ditambahkan.',
            'items.*.id_barang.required'    => 'ID Barang wajib dipilih.',
            'items.*.nama_barang.required'  => 'Nama Barang wajib diisi.',
            'items.*.jumlah_barang.required'=> 'Jumlah Barang wajib diisi.',
            'items.*.jumlah_barang.min'     => 'Jumlah Barang minimal 1.',
        ]);

        $buktiFile = null;
        if ($request->hasFile('bukti_file')) {
            $file      = $request->file('bukti_file');
            $filename  = $file->getClientOriginalName();
            $buktiFile = $file->storeAs('bukti-masuk', $filename, 'public');
        }

        foreach ($request->items as $item) {
            BarangMasuk::create([
                'nomor_faktur'  => $request->nomor_faktur,
                'id_barang'     => $item['id_barang'],
                'nama_barang'   => $item['nama_barang'],
                'tanggal_masuk' => $request->tanggal_masuk,
                'jumlah'   => $item['jumlah_barang'],
                'bukti_file'    => $buktiFile,
            ]);
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $request->validate([
            'nomor_faktur'              => 'required|string|max:100',
            'tanggal_masuk'             => 'required|date',
            'bukti_file'                => 'nullable|file|mimes:pdf|max:5120',
            'items'                     => 'required|array|min:1',
            'items.*.id_barang'         => 'required|string|max:50',
            'items.*.nama_barang'       => 'required|string|max:255',
            'items.*.jumlah_barang'     => 'required|integer|min:1',
        ], [
            'nomor_faktur.required'          => 'Nomor Faktur wajib diisi.',
            'tanggal_masuk.required'         => 'Tanggal Masuk wajib diisi.',
            'bukti_file.mimes'               => 'Bukti File harus berupa PDF.',
            'bukti_file.max'                 => 'Ukuran file maksimal 5 MB.',
            'items.required'                 => 'Minimal satu barang harus ditambahkan.',
            'items.*.id_barang.required'     => 'ID Barang wajib dipilih.',
            'items.*.nama_barang.required'   => 'Nama Barang wajib diisi.',
            'items.*.jumlah_barang.required' => 'Jumlah Barang wajib diisi.',
            'items.*.jumlah_barang.min'      => 'Jumlah Barang minimal 1.',
        ]);

        // Tentukan bukti file: pakai yang baru jika diupload, atau pertahankan lama
        $oldNomorFaktur = $barangMasuk->nomor_faktur;
        $oldBuktiFile   = $barangMasuk->bukti_file;

        $buktiFile = $oldBuktiFile;
        if ($request->hasFile('bukti_file')) {
            if ($oldBuktiFile) {
                Storage::disk('public')->delete($oldBuktiFile);
            }
            $file      = $request->file('bukti_file');
            $filename  = $file->getClientOriginalName();
            $buktiFile = $file->storeAs('bukti-masuk', $filename, 'public');
        }

        // Hapus semua record lama dengan nomor_faktur yang sama, lalu buat ulang
        BarangMasuk::where('nomor_faktur', $oldNomorFaktur)->delete();

        foreach ($request->items as $item) {
            BarangMasuk::create([
                'nomor_faktur'  => $request->nomor_faktur,
                'id_barang'     => $item['id_barang'],
                'nama_barang'   => $item['nama_barang'],
                'tanggal_masuk' => $request->tanggal_masuk,
                'jumlah'   => $item['jumlah_barang'],
                'bukti_file'    => $buktiFile,
            ]);
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil diperbarui.');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        $nomorFaktur = $barangMasuk->nomor_faktur;
        if ($barangMasuk->bukti_file) {
            Storage::disk('public')->delete($barangMasuk->bukti_file);
        }
        BarangMasuk::where('nomor_faktur', $nomorFaktur)->delete();
        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil dihapus.');
    }
    public function show($nomor_faktur)
{
    $items = BarangMasuk::where(
        'nomor_faktur',
        $nomor_faktur
    )->get();

    return response()->json($items);
}
}
