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
        $query = Pengeluaran::with(['pegawai', 'income']); // Tambahkan 'income'

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

    public function create(Request $request)
    {
        $struks = Struk::all();
        $pegawais = Pegawai::all();
        $income = null;

        // Kalau ada income_id di query, ambil struk terkait
        if ($request->has('income_id')) {
            $income = Struk::find($request->income_id);
        }

        return view('pengeluarans.create', compact('struks', 'pegawais', 'income'));
    }

    public function store(Request $request)
    {
        // Validasi dasar yang berlaku untuk semua jenis pengeluaran
        $baseRules = [
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Jika pengeluaran dari pemasukan
        if ($request->has('from_income')) {
            $validated = $request->validate(array_merge($baseRules, [
                'struk_id' => 'required|exists:struks,id',
            ]));

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
                'struk_id' => $validated['struk_id'],
                'bukti_pembayaran' => $struk->foto_struk ? 'struk_foto/' . $struk->foto_struk : null,
            ];

            // Handle file upload
            if ($request->hasFile('bukti_pembayaran')) {
                $pengeluaranData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('pengeluaran_bukti', 'public');
            }

            Pengeluaran::create($pengeluaranData);

            return redirect()->route('pengeluarans.index')->with('success', 'Pengeluaran berhasil dibuat dari pemasukan.');
        }

        // Jika pengeluaran manual
        $validated = $request->validate(array_merge($baseRules, [
            'nama_toko' => 'required|string',
            'nomor_struk' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|integer|min:0',
        ]));

        $total = collect($validated['items'])->sum(fn($item) => $item['jumlah'] * $item['harga']);

        $pengeluaranData = [
            'nama_toko' => $validated['nama_toko'],
            'nomor_struk' => $validated['nomor_struk'],
            'tanggal' => $validated['tanggal'],
            'pegawai_id' => $validated['pegawai_id'],
            'daftar_barang' => json_encode($validated['items']),
            'total' => $total,
            'jumlah_item' => collect($validated['items'])->sum('jumlah'),
            'keterangan' => $validated['keterangan'] ?? null,
            'struk_id' => null, // Manual expenses don't need struk_id
        ];

        // Handle file upload
        if ($request->hasFile('bukti_pembayaran')) {
            $pengeluaranData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('pengeluaran_bukti', 'public');
        }

        Pengeluaran::create($pengeluaranData);

        return redirect()->route('pengeluarans.index')->with('success', 'Pengeluaran manual berhasil dibuat.');
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
        $struks = Struk::all();
        return view('struks.pengeluarans.edit', compact('pengeluaran', 'pegawais', 'struks'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $rules = [
            'nama_toko' => 'required|string',
            'nomor_struk' => 'required|string',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Jika pengeluaran manual dan ingin mengupdate items
        if ($pengeluaran->struk_id === null && $request->has('items')) {
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.nama'] = 'required|string';
            $rules['items.*.jumlah'] = 'required|integer|min:1';
            $rules['items.*.harga'] = 'required|integer|min:0';
        }

        $validated = $request->validate($rules);

        $updateData = [
            'nama_toko' => $validated['nama_toko'],
            'nomor_struk' => $validated['nomor_struk'],
            'pegawai_id' => $validated['pegawai_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
        ];

        // Jika pengeluaran manual dan ada items
        if ($pengeluaran->struk_id === null && isset($validated['items'])) {
            $total = collect($validated['items'])->sum(fn($item) => $item['jumlah'] * $item['harga']);
            $updateData['daftar_barang'] = json_encode($validated['items']);
            $updateData['total'] = $total;
            $updateData['jumlah_item'] = collect($validated['items'])->sum('jumlah');
        }

        // Handle file upload
        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old file if exists
            if ($pengeluaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pengeluaran->bukti_pembayaran);
            }
            $updateData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('pengeluaran_bukti', 'public');
        }

        $pengeluaran->update($updateData);

        return redirect()->route('pengeluarans.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        // Delete associated file if exists
        if ($pengeluaran->bukti_pembayaran) {
            Storage::disk('public')->delete($pengeluaran->bukti_pembayaran);
        }

        $pengeluaran->delete();
        return back()->with('success', 'Pengeluaran berhasil dihapus');
    }
}
