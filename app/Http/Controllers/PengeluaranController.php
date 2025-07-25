<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
        $query = Pengeluaran::with(['pegawai', 'income']);

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_toko', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_struk', 'like', '%' . $request->search . '%')
                ->orWhereHas('pegawai', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                });
        }

        $pengeluarans = $query->latest()->paginate(10);
        $barangs = \App\Models\Barang::pluck('nama_barang', 'kode_barang'); // kode => nama

        return view('struks.pengeluarans.index', [
            'pengeluarans' => $pengeluarans,
            'struks' => Struk::paginate(10),
            'barangs' => $barangs,
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
        $validated = $request->validate([
            'nama_toko' => 'required|string',
            'nomor_struk' => 'required|string',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string', // ini adalah kode_barang
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|integer|min:0',
            'total' => 'required|numeric',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Validasi stok
        foreach ($validated['items'] as $item) {
            $kodeBarang = $item['nama']; // kode_barang
            $jumlah = $item['jumlah'];

            $barang = \App\Models\Barang::where('kode_barang', $kodeBarang)->first();
            if (!$barang) {
                return back()->withErrors(['Barang dengan kode ' . $kodeBarang . ' tidak ditemukan.']);
            }

            if ($barang->jumlah < $jumlah) {
                return back()->withErrors([
                    'Stok barang "' . $barang->nama_barang . '" tidak mencukupi. Stok tersedia: ' . $barang->jumlah
                ]);
            }
        }

        // Jika semua valid, kurangi stok
        foreach ($validated['items'] as $item) {
            $kodeBarang = $item['nama'];
            $jumlah = $item['jumlah'];

            $barang = \App\Models\Barang::where('kode_barang', $kodeBarang)->first();
            $barang->jumlah -= $jumlah;
            $barang->save();
        }

        // Simpan pengeluaran
        $pengeluaranData = [
            'nama_toko' => $validated['nama_toko'],
            'nomor_struk' => $validated['nomor_struk'],
            'pegawai_id' => $validated['pegawai_id'],
            'tanggal' => $validated['tanggal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'daftar_barang' => json_encode($validated['items']),
            'total' => $validated['total'],
            'jumlah_item' => collect($validated['items'])->sum('jumlah'),
        ];

        // Upload bukti pembayaran jika ada
        if ($request->hasFile('bukti_pembayaran')) {
            $pengeluaranData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('pengeluaran_bukti', 'public');
        }

        \App\Models\Pengeluaran::create($pengeluaranData);

        return redirect()->route('pengeluarans.index')->with('created', 'Pengeluaran berhasil disimpan dan stok dikurangi.');
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
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($pengeluaran->struk_id === null) {
            $rules['existing_items.*.kode_barang'] = 'required|string';
            $rules['existing_items.*.jumlah'] = 'required|integer|min:1';
            $rules['existing_items.*.harga'] = 'required|integer|min:0';

            $rules['new_items.*.kode_barang'] = 'required|string';
            $rules['new_items.*.jumlah'] = 'required|integer|min:1';
            $rules['new_items.*.harga'] = 'required|integer|min:0';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $pengeluaran) {
            $updateData = [
                'nama_toko' => $validated['nama_toko'],
                'nomor_struk' => $validated['nomor_struk'],
                'pegawai_id' => $validated['pegawai_id'],
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            if ($pengeluaran->struk_id === null) {
                $combinedItems = [];

                // Ambil daftar barang lama
                $oldItems = is_string($pengeluaran->daftar_barang)
                    ? json_decode($pengeluaran->daftar_barang, true)
                    : ($pengeluaran->daftar_barang ?? []);

                // Proses existing items
                if ($request->has('existing_items')) {
                    foreach ($request->existing_items as $item) {
                        $combinedItems[] = [
                            'nama' => $item['kode_barang'],
                            'kode_barang' => $item['kode_barang'],
                            'jumlah' => $item['jumlah'],
                            'harga' => $item['harga']
                        ];
                    }
                }

                // Proses new items
                if ($request->has('new_items')) {
                    foreach ($request->new_items as $item) {
                        $combinedItems[] = [
                            'nama' => $item['kode_barang'],
                            'kode_barang' => $item['kode_barang'],
                            'jumlah' => $item['jumlah'],
                            'harga' => $item['harga']
                        ];
                    }
                }

                // Validasi dan update stok
                $total = 0;
                $jumlahItem = 0;
                $finalItems = [];

                foreach ($combinedItems as $item) {
                    $kodeBarang = $item['kode_barang'];
                    $jumlahBaru = $item['jumlah'];
                    $harga = $item['harga'];

                    $barang = Barang::where('kode_barang', $kodeBarang)->first();
                    if ($barang) {
                        // Cari jumlah sebelumnya (lama)
                        $jumlahLama = 0;
                        foreach ($oldItems as $old) {
                            $kodeLama = $old['kode_barang'] ?? $old['nama'];
                            if ($kodeLama == $kodeBarang) {
                                $jumlahLama = $old['jumlah'];
                                break;
                            }
                        }

                        // Hitung selisih
                        $selisih = $jumlahBaru - $jumlahLama;

                        // Jika selisih positif, berarti minta tambahan stok
                        if ($selisih > 0 && $barang->jumlah < $selisih) {
                            throw new \Exception("Stok untuk barang {$barang->nama_barang} tidak cukup. Stok tersedia: {$barang->jumlah}, tambahan diminta: {$selisih}");
                        }

                        // Update stok sesuai selisih
                        $barang->jumlah -= $selisih;
                        $barang->save();

                        $item['nama'] = $barang->nama_barang;
                    }

                    $total += $jumlahBaru * $harga;
                    $jumlahItem += $jumlahBaru;
                    $finalItems[] = $item;
                }

                $updateData['daftar_barang'] = json_encode($finalItems);
                $updateData['total'] = $total;
                $updateData['jumlah_item'] = $jumlahItem;
            }

            // Upload bukti pembayaran jika ada
            if ($request->hasFile('bukti_pembayaran')) {
                if ($pengeluaran->bukti_pembayaran) {
                    Storage::disk('public')->delete($pengeluaran->bukti_pembayaran);
                }
                $updateData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('pengeluaran_bukti', 'public');
            }

            $pengeluaran->update($updateData);
        });

        return redirect()->route('pengeluarans.index')->with('updated', 'Pengeluaran berhasil diperbarui dan stok diperbarui.');
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
