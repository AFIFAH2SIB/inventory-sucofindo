<?php

namespace App\Http\Controllers;

use App\Models\ManajemenBarang;
use Illuminate\Http\Request;

class ManajemenBarangController extends Controller
{
    public function index()
    {
        $barang = ManajemenBarang::latest()->get();

        return view('manajemen-barang.index', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang'   => 'required|unique:manajemen_barang,id_barang',
            'nama_barang' => 'required'
        ]);

        ManajemenBarang::create([
            'id_barang'    => $request->id_barang,
            'nama_barang'  => $request->nama_barang,
            'lead_time'    => 0,
            'safety_stock' => 0
        ]);

        return redirect()
            ->route('manajemen-barang')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_barang'   => 'required',
            'nama_barang' => 'required'
        ]);

        $barang = ManajemenBarang::findOrFail($id);

        $barang->update([
            'id_barang'   => $request->id_barang,
            'nama_barang' => $request->nama_barang
        ]);

        return redirect()
            ->route('manajemen-barang')
            ->with('success', 'Barang berhasil diupdate');
    }

    public function destroy($id)
    {
        $barang = ManajemenBarang::findOrFail($id);

        $barang->delete();

        return redirect()
            ->route('manajemen-barang')
            ->with('success', 'Barang berhasil dihapus');
    }
}