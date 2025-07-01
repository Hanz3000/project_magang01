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
        $pengeluarans = Pengeluaran::latest()->paginate(10);
        return view('struks.pengeluarans.index', compact('pengeluarans'));
    }

    // Nested Index
    public function indexByStruk(Struk $struk)
    {
        $pengeluarans = $struk->pengeluarans()->with('pegawai')->latest()->get();
        return view('pengeluarans.index', compact('pengeluarans', 'struk')); // Diubah dari struks.pengeluaran.index
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
        return view('pengeluarans.create', compact('struk', 'pegawais')); // Diubah dari struks.pengeluaran.create
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
        $validated = $request->validate([
            'struk_id' => 'required|exists:struks,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'items' => 'required|array',
            'items.*.nama' => 'required',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|max:2048'
        ]);

        $struk = Struk::findOrFail($validated['struk_id']);
        $total = collect($validated['items'])->sum(function ($item) {
            return $item['jumlah'] * $item['harga'];
        });

        $pengeluaranData = [
            'nama_toko' => $struk->nama_toko,
            'nomor_struk' => $struk->nomor_struk,
            'tanggal' => $validated['tanggal'],
            'pegawai_id' => $validated['pegawai_id'],
            'daftar_barang' => json_encode($validated['items']),
            'total' => $total,
            'jumlah_item' => count($validated['items']),
            'keterangan' => $validated['keterangan'] ?? null,
        ];

        if ($request->hasFile('bukti_pembayaran')) {
            $pengeluaranData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('bukti-pembayaran');
        }

        Pengeluaran::create($pengeluaranData);

        return redirect()->route('pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil dibuat dari pemasukan');
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

    public function exportExcel()
    {
        // Implementasi export Excel di sini
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }

    public function exportCsv()
    {
        // Implementasi export CSV di sini
        return response()->json(['message' => 'Export CSV belum diimplementasikan']);
    }
}
