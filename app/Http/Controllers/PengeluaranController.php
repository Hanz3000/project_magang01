<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pengeluaran;
use App\Models\Struk;
use App\Models\Pegawai;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;


class PengeluaranController extends Controller
{
    public function index(Request $request)
{
    // Mulai dari query, tapi eksklusif hanya yang belum selesai
    $query = Pengeluaran::with(['pegawai', 'income'])->where('status', '!=', 'completed');

    // Filter pencarian
    if ($request->has('search') && $request->search != '') {
        $searchTerm = strtolower($request->search);
        $query->where(function($q) use ($searchTerm) {
            $q->whereRaw('LOWER(nama_toko) LIKE ?', ['%' . $searchTerm . '%'])
              ->orWhereRaw('LOWER(nomor_struk) LIKE ?', ['%' . $searchTerm . '%'])
              ->orWhereHas('pegawai', function ($q) use ($searchTerm) {
                  $q->whereRaw('LOWER(nama) LIKE ?', ['%' . $searchTerm . '%']);
              });
        });
    }

    // Filter status (opsional: progress / in_progress)
    $status = $request->get('status');
    if ($status === 'in_progress') {
        $query->where('status', 'progress');
    }
    // Jika ingin tampilkan semua yang belum completed, biarkan tanpa filter tambahan

    $pengeluarans = $query->latest()->paginate(10)->appends($request->query());
    $barangs = Barang::pluck('nama_barang', 'kode_barang');

    return view('struks.pengeluarans.index', [
        'pengeluarans' => $pengeluarans,
        'barangs' => $barangs,
    ]);
}

    public function create(Request $request)
    {
        $struks = Struk::all();
        $pegawais = Pegawai::all();
        $income = $request->has('income_id') ? Struk::find($request->income_id) : null;
        $barangList = Barang::all();

        // Generate preview of the next SPK number
        $nextSpkNumber = $this->generateNomorStruk();

        return view('pengeluarans.create', compact('struks', 'pegawais', 'income', 'barangList', 'nextSpkNumber'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'pegawai_id' => 'required|exists:pegawais,id',
        'tanggal' => 'required|date',
        'keterangan' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.nama' => 'required|string',
        'items.*.jumlah' => 'required|integer|min:1',
        'status' => 'required|in:progress,completed', // ✅ Tambahkan validasi
    ]);

    // Generate nomor struk otomatis
    $validated['nomor_struk'] = $this->generateNomorStruk();

    // Generate Nama SPK dan simpan di kolom nama_toko
    $validated['nama_toko'] = $this->generateNamaSpkFromPegawaiId($validated['pegawai_id']);

    // Validasi stok barang
    foreach ($validated['items'] as $item) {
        $kodeBarang = $item['nama'];
        $jumlah = $item['jumlah'];

        $barang = Barang::where('kode_barang', $kodeBarang)->first();
        if (!$barang) {
            return back()->withErrors(['Barang dengan kode ' . $kodeBarang . ' tidak ditemukan.']);
        }

        if ($barang->jumlah < $jumlah) {
            return back()->withErrors([
                'Stok barang "' . $barang->nama_barang . '" tidak mencukupi. Stok tersedia: ' . $barang->jumlah
            ]);
        }
    }

    // Kurangi stok barang
    foreach ($validated['items'] as $item) {
        $barang = Barang::where('kode_barang', $item['nama'])->first();
        $barang->jumlah -= $item['jumlah'];
        $barang->save();
    }

    $pengeluaranData = [
        'nama_toko' => $validated['nama_toko'],
        'nomor_struk' => $validated['nomor_struk'],
        'pegawai_id' => $validated['pegawai_id'],
        'tanggal' => $validated['tanggal'],
        'keterangan' => $validated['keterangan'] ?? null,
        'daftar_barang' => json_encode($validated['items']),
        'jumlah_item' => collect($validated['items'])->sum('jumlah'),
        'status' => $validated['status'], // ✅ Sudah ambil dari form
    ];

    try {
        Pengeluaran::create($pengeluaranData);
    } catch (\Illuminate\Database\QueryException $e) {
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            return back()->withErrors(['Nomor struk sudah digunakan. Silahkan coba lagi.']);
        }
        throw $e;
    }

    return redirect()->route('pengeluarans.index')->with('created', 'Pengeluaran berhasil disimpan dan stok dikurangi.');
}

    public function show($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        // Ambil semua data barang dan ubah menjadi array dengan key = kode_barang
        $masterBarang = Barang::all()->keyBy('kode_barang');

        return view('struks.pengeluarans.show', compact('pengeluaran', 'masterBarang'));
    }

    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pegawais = Pegawai::all();
        $barangs = Barang::all();

        return view('struks.pengeluarans.edit', compact('pengeluaran', 'pegawais', 'barangs'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $rules = [
            'nama_toko' => 'required|string',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:progress,completed',
        ];

        // Validasi jika input manual (bukan dari struk)
        if ($pengeluaran->struk_id === null) {
            $rules['existing_items.*.kode_barang'] = 'required|string';
            $rules['existing_items.*.jumlah'] = 'required|integer|min:1';

            $rules['new_items.*.kode_barang'] = 'required|string';
            $rules['new_items.*.jumlah'] = 'required|integer|min:1';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $pengeluaran) {
            $updateData = [
                'nama_toko' => $validated['nama_toko'],
                'pegawai_id' => $validated['pegawai_id'],
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => $validated['status'],
            ];

            // Jika bukan dari struk, update daftar_barang dan stok
            if ($pengeluaran->struk_id === null) {
                $combinedItems = [];

                // Ambil item lama dari data JSON
                $oldItems = is_string($pengeluaran->daftar_barang)
                    ? json_decode($pengeluaran->daftar_barang, true)
                    : ($pengeluaran->daftar_barang ?? []);

                // Proses existing_items
                if ($request->has('existing_items')) {
                    foreach ($request->existing_items as $item) {
                        $combinedItems[] = [
                            'nama' => $item['kode_barang'],
                            'kode_barang' => $item['kode_barang'],
                            'jumlah' => $item['jumlah'],
                        ];
                    }
                }

                // Proses new_items
                if ($request->has('new_items')) {
                    foreach ($request->new_items as $item) {
                        $combinedItems[] = [
                            'nama' => $item['kode_barang'],
                            'kode_barang' => $item['kode_barang'],
                            'jumlah' => $item['jumlah'],
                        ];
                    }
                }

                $jumlahItem = 0;
                $finalItems = [];

                foreach ($combinedItems as $item) {
                    $kodeBarang = $item['kode_barang'];
                    $jumlahBaru = $item['jumlah'];

                    $barang = Barang::where('kode_barang', $kodeBarang)->first();
                    if ($barang) {
                        // Cek jumlah lama jika ada
                        $jumlahLama = 0;
                        foreach ($oldItems as $old) {
                            $kodeLama = $old['kode_barang'] ?? $old['nama'];
                            if ($kodeLama == $kodeBarang) {
                                $jumlahLama = $old['jumlah'];
                                break;
                            }
                        }

                        $selisih = $jumlahBaru - $jumlahLama;

                        if ($selisih > 0 && $barang->jumlah < $selisih) {
                            throw new \Exception("Stok untuk barang {$barang->nama_barang} tidak cukup. Tersedia: {$barang->jumlah}, tambahan diminta: {$selisih}");
                        }

                        $barang->jumlah -= $selisih;
                        $barang->save();

                        $item['nama'] = $barang->nama_barang;
                    }

                    $jumlahItem += $jumlahBaru;
                    $finalItems[] = $item;
                }

                $updateData['daftar_barang'] = json_encode($finalItems);
                $updateData['jumlah_item'] = $jumlahItem;
            }

            // Simpan data pengeluaran
            $pengeluaran->update($updateData);
        });

        return redirect()->route('pengeluarans.index')->with('updated', 'Pengeluaran berhasil diperbarui dan stok diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return back()->with('deleted', 'Pengeluaran berhasil dihapus');
    }

    public function massDelete(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string'
        ]);

        $selectedIds = json_decode($request->selected_ids);

        if (!is_array($selectedIds)) {
            return back()->with('error', 'Data tidak valid');
        }

        Pengeluaran::whereIn('id', $selectedIds)->delete();

        return back()->with('success', count($selectedIds) . ' data pengeluaran berhasil dihapus');
    }

    private function generateNomorStruk()
{
    $today = Carbon::today();
    $dayMonth = $today->format('d/m');     // 29/07
    $year = $today->format('y');           // 25
    $prefix = 'SPK/' . $dayMonth . '/' . $year;

    // Cari nomor struk terakhir hari ini
    $last = Pengeluaran::where('nomor_struk', 'like', $prefix . '%')
        ->orderBy('nomor_struk', 'desc')
        ->first();

    $sequence = 1;

    if ($last) {
        // Ambil 6 digit terakhir dari nomor struk
        $lastNumber = substr($last->nomor_struk, -6); // Ex: 000003
        if (is_numeric($lastNumber)) {
            $sequence = (int)$lastNumber + 1;
        }
    }

    $sequenceFormatted = str_pad($sequence, 6, '0', STR_PAD_LEFT); // 000004

    return $prefix . $sequenceFormatted;
}


    // Tambahkan method ini:
    public function ajaxGenerateNomorStruk(Request $request)
    {
        $nomor = $this->generateNomorStruk();
        return response()->json(['nomor_struk' => $nomor]);
    }


public function generateNamaSpkString(Request $request)
{
    $request->validate([
        'pegawai_id' => 'required|exists:pegawais,id'
    ]);

    $pegawai = Pegawai::with('divisi')->findOrFail($request->pegawai_id);

    $divisi = $pegawai->divisi ? substr(strtoupper($pegawai->divisi->name), 0, 3) : 'XXX';
    $nip = substr($pegawai->nip, -2);
    $count = Pengeluaran::where('pegawai_id', $pegawai->id)->count() + 1;
    $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);

    $namaSpk = "SPK-{$divisi}-{$nip}-{$sequence}";

    return response()->json([
        'nama_spk' => $namaSpk,
        'divisi' => $pegawai->divisi ? $pegawai->divisi->name : 'Tidak diketahui'
    ]);
}

private function generateNamaSpkFromPegawaiId($pegawaiId)
{
    $pegawai = \App\Models\Pegawai::with('divisi')->findOrFail($pegawaiId);
    $divisi = $pegawai->divisi ? substr(strtoupper($pegawai->divisi->name), 0, 3) : 'XXX';
    $nip = substr($pegawai->nip, -2);

    // Ambil SPK terakhir berdasarkan nomor SPK pegawai itu
    $lastSpk = \App\Models\Pengeluaran::where('pegawai_id', $pegawaiId)
        ->where('nama_toko', 'like', "SPK-$divisi-$nip-%")
        ->orderBy('created_at', 'desc')
        ->first();

    $lastNumber = 0;

    if ($lastSpk) {
        // Ambil 3 digit terakhir dari nama SPK
        $matches = [];
        if (preg_match('/SPK-' . $divisi . '-' . $nip . '-(\d+)/', $lastSpk->nama_toko, $matches)) {
            $lastNumber = (int)$matches[1];
        }
    }

    $sequence = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

    return "SPK-{$divisi}-{$nip}-{$sequence}";
}



}
