<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class MasterBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Barang::query();

        if ($request->filled('q')) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%');
        }

        $barangs = $query->get();

        return view('master_barang.index', compact('barangs'))  ->with('success', 'Barang berhasil ditambahkan');
    }   

    public function create()
    {
        return view('master_barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
        ]);

        Barang::create([
            'nama_barang' => $request->nama_barang
        ]);

        // Tambahkan pengecekan action
        if ($request->has('action') && $request->action === 'save_and_continue') {
            return redirect()->back()->with('created', 'Barang berhasil ditambahkan. Silakan tambah barang baru.');
        }

        return redirect()->route('master-barang.index')->with('created', 'Barang berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('master_barang.edit', compact('barang'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama_barang' => $request->nama_barang
        ]);

        return redirect()->route('master-barang.index')->with('updated', 'Barang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('master-barang.index')->with('deleted', 'Barang berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if ($ids) {
            \App\Models\Barang::whereIn('id', $ids)->delete();
            return redirect()->route('master-barang.index')->with('success', 'Barang terpilih berhasil dihapus.');
        }

        return redirect()->route('master-barang.index')->with('success', 'Tidak ada barang yang dipilih.');
    }
}
