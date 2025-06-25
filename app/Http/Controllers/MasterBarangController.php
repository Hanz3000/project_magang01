<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class MasterBarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('master_barang.index', compact('barangs'));
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

        return redirect()->route('master-barang.index')->with('success', 'Barang berhasil ditambahkan.');
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

        return redirect()->route('master-barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('master-barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
