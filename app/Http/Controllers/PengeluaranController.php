<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Struk;
use App\Models\Pegawai;
use App\Models\Barang; // Pastikan sudah di-import
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
        return view('struks.pengeluarans.index', [
            'pengeluarans' => $pengeluarans,
            'struks' => Struk::paginate(10), // Ubah dari all() ke paginate()
        ]);
    }

    public function create(Request $request)
    {
        $struks = Struk::all();
        $pegawais = Pegawai::all();
        $income = null;
        $barangList = Barang::all(); // Tambahkan baris ini

        // Kalau ada income_id di query, ambil struk terkait
        if ($request->has('income_id')) {
            $income = Struk::find($request->income_id);
        }

        return view('pengeluarans.create', compact('struks', 'pegawais', 'income', 'barangList')); // Tambahkan 'barangList'
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
                'tanggal_struk' => 'required|date', // Pastikan divalidasi juga
            ]));

            $struk = Struk::findOrFail($validated['struk_id']);
            $items = json_decode($struk->items, true) ?? [];
            $total = collect($items)->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));

            $pengeluaranData = [
                'nama_toko' => $struk->nama_toko,
                'nomor_struk' => $struk->nomor_struk,
                'tanggal' => $validated['tanggal'],
                'tanggal_struk' => $validated['tanggal_struk'], // <-- Tambahkan ini!
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

            if ($request->has('from_income') && $request->from_income == 1) {
                // Hapus pemasukan (struk) yang dipilih
                $struk = Struk::find($request->struk_id);
                if ($struk) {
                    $struk->delete();
                }
            }

            // Redirect ke index pengeluaran
            return redirect()->route('pengeluarans.index')->with('created', 'Pengeluaran berhasil disimpan!');
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
        $barangs = Barang::all(); // tambahkan ini

        return view('struks.pengeluarans.edit', compact('pengeluaran', 'pegawais', 'barangs'));
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

        return redirect()->route('pengeluarans.index')->with('updated', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        // Delete associated file if exists
        if ($pengeluaran->bukti_pembayaran) {
            Storage::disk('public')->delete($pengeluaran->bukti_pembayaran);
        }

        $pengeluaran->delete();
        return back()->with('deleted', 'Pengeluaran berhasil dihapus');
    }

    public function massDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string'
        ]);

        $selectedIds = json_decode($request->selected_ids);

        // Validasi input
        if (!is_array($selectedIds)) {
            return back()->with('error', 'Data tidak valid');
        }

        // Hapus file bukti pembayaran terlebih dahulu
        $pengeluarans = Pengeluaran::whereIn('id', $selectedIds)->get();
        foreach ($pengeluarans as $pengeluaran) {
            if ($pengeluaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pengeluaran->bukti_pembayaran);
            }
        }

        // Hapus data dari database
        Pengeluaran::whereIn('id', $selectedIds)->delete();

        return back()->with('success', count($selectedIds) . ' data pengeluaran berhasil dihapus');
    }
}
