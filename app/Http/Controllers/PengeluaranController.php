<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Struk;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('pegawai');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_toko', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_struk', 'like', '%' . $request->search . '%')
                ->orWhereHas('pegawai', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                });
        }

        $pengeluarans = $query->latest()->paginate(10);
        return view('struks.pengeluarans.index', compact('pengeluarans'));
    }

    public function indexByStruk(Struk $struk)
    {
        $pengeluarans = $struk->pengeluarans()->with('pegawai')->latest()->paginate(10);
        return view('pengeluarans.index', compact('pengeluarans', 'struk'));
    }

    public function create()
    {
        $struks = Struk::all();
        $pegawais = Pegawai::all();
        return view('pengeluarans.create', compact('struks', 'pegawais'));
    }

    public function createByStruk(Struk $struk)
    {
        $pegawais = Pegawai::all();
        return view('pengeluarans.create', compact('struk', 'pegawais'));
    }

    public function storeByStruk(Request $request, Struk $struk)
    {
        $validated = $request->validate([
            'struk_id' => 'required|exists:struks,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $items = json_decode($struk->items, true) ?? [];
        $total = collect($items)->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));

        $pengeluaran = $struk->pengeluarans()->create([
            'nama_toko' => $struk->nama_toko,
            'nomor_struk' => $struk->nomor_struk,
            'tanggal' => $validated['tanggal'],
            'pegawai_id' => $validated['pegawai_id'],
            'daftar_barang' => json_encode($items),
            'total' => $total,
            'jumlah_item' => count($items),
            'bukti_pembayaran' => $struk->foto_struk,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('struks.pengeluarans.index', $struk)
            ->with('success', 'Pengeluaran berhasil dibuat dari struk.');
    }

    public function store(Request $request)
    {
        if ($request->has('from_income')) {
            $validated = $request->validate([
                'struk_id' => 'required|exists:struks,id',
                'pegawai_id' => 'required|exists:pegawais,id',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string',
            ]);

            $struk = Struk::findOrFail($validated['struk_id']);
            $items = json_decode($struk->items, true) ?? [];
            $total = collect($items)->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));

            $pengeluaranData = [
                'nama_toko' => $struk->nama_toko,
                'nomor_struk' => $struk->nomor_struk,
                'tanggal' => $validated['tanggal'],
                'pegawai_id' => $validated['pegawai_id'],
                'daftar_barang' => json_encode($items),
                'total' => $total,
                'jumlah_item' => count($items),
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            if ($request->hasFile('bukti_pembayaran')) {
                $pengeluaranData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('struk_foto', 'public');
            }

            Pengeluaran::create($pengeluaranData);

            return redirect()->route('pengeluarans.index')->with('success', 'Pengeluaran berhasil dibuat dari pemasukan.');
        }

        $validated = $request->validate([
            'struk_id' => 'required|exists:struks,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $total = collect($validated['items'])->sum(fn($item) => $item['jumlah'] * $item['harga']);

        $pengeluaranData = [
            'nama_toko' => $request->nama_toko,
            'nomor_struk' => $request->nomor_struk,
            'tanggal' => $validated['tanggal'],
            'pegawai_id' => $validated['pegawai_id'],
            'daftar_barang' => json_encode($validated['items']),
            'total' => $total,
            'jumlah_item' => collect($validated['items'])->sum('jumlah'),
            'keterangan' => $validated['keterangan'] ?? null,
        ];

        if ($request->hasFile('bukti_pembayaran')) {
            $pengeluaranData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('struk_foto', 'public');
        }

        Pengeluaran::create($pengeluaranData);

        return redirect()->route('pengeluarans.index')->with('success', 'Pengeluaran berhasil dibuat.');
    }

    public function show($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('struks.pengeluarans.show', compact('pengeluaran'));
    }

    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pegawais = Pegawai::all();
        return view('struks.pengeluarans.edit', compact('pengeluaran', 'pegawais'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string',
            'nomor_struk' => 'required|string',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $pengeluaran->update($validated);

        return redirect()->route('pengeluarans.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus');
    }

    public function exportExcel()
    {
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }

    public function exportCsv()
    {
        return response()->json(['message' => 'Export CSV belum diimplementasikan']);
    }
}
