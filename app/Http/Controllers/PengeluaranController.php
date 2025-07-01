<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Struk;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    // Standalone Index
    public function index()
    {
        $pengeluarans = Pengeluaran::with('pegawai')->latest()->get();
        return view('pengeluarans.index', compact('pengeluarans'));
    }

    // Nested Index
    public function indexByStruk(Struk $struk)
    {
        $pengeluarans = $struk->pengeluarans()->with('pegawai')->latest()->get();
        return view('struks.pengeluaran.index', compact('pengeluarans', 'struk'));
    }

    // Standalone Create
    public function create()
    {
        $struks = Struk::all();
        $pegawais = Pegawai::all();
        return view('pengeluarans.create', compact('struks', 'pegawais'));
    }

    // Nested Create
    public function createByStruk(Struk $struk)
    {
        $pegawais = Pegawai::all();
        return view('pengeluarans.create', compact('struk', 'pegawais'));
    }

    // Store from nested route
    public function storeByStruk(Request $request, Struk $struk)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $pengeluaran = $struk->pengeluarans()->create([
            'nama_toko' => $struk->nama_toko,
            'nomor_struk' => $struk->nomor_struk,
            'tanggal' => $validated['tanggal'],
            'pegawai_id' => $validated['pegawai_id'],
            'daftar_barang' => $struk->items,
            'total' => $struk->total_harga,
            'jumlah_item' => count(json_decode($struk->items, true)),
            'bukti_pembayaran' => $struk->foto_struk,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('struks.pengeluarans.index', $struk)
            ->with('success', 'Pengeluaran berhasil dibuat dari struk.');
    }

    // Store from standalone route
    public function store(Request $request)
    {
        $validated = $request->validate([
            'struk_id' => 'required|exists:struks,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $struk = Struk::findOrFail($validated['struk_id']);

        $pengeluaran = Pengeluaran::create([
            'nama_toko' => $struk->nama_toko,
            'nomor_struk' => $struk->nomor_struk,
            'tanggal' => $validated['tanggal'],
            'pegawai_id' => $validated['pegawai_id'],
            'daftar_barang' => $struk->items,
            'total' => $struk->total_harga,
            'jumlah_item' => count(json_decode($struk->items, true)),
            'bukti_pembayaran' => $struk->foto_struk,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil dibuat.');
    }

    public function storeFromIncome(Request $request)
    {
        // ... keep your existing storeFromIncome implementation ...
    }

    public function show(Pengeluaran $pengeluaran)
    {
        return view('pengeluarans.show', compact('pengeluaran'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $pegawais = Pegawai::all();
        return view('pengeluarans.edit', compact('pengeluaran', 'pegawais'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $pengeluaran->update($validated);

        return redirect()->route('pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus');
    }
}
