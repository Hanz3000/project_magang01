<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Pengeluaran;
use App\Models\Struk;
use App\Models\Pegawai;
use App\Models\Barang;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with(['pegawai']);

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_toko', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_struk', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pegawai', function ($q) use ($request) {
                      $q->where('nama', 'like', '%' . $request->search . '%');
                  });
        }

        $pengeluarans = $query->latest()->paginate(10);
        $barangs = Barang::pluck('nama_barang', 'kode_barang');
        $struks = Struk::all();

        return view('struks.pengeluarans.index', [
            'pengeluarans' => $pengeluarans,
            'barangs' => $barangs,
            'struks' => $struks,
        ]);
    }

    public function create(Request $request)
    {
        $struks = Struk::all();
        $pegawais = Pegawai::all();
        $income = $request->has('income_id') ? Struk::find($request->income_id) : null;
        $barangList = Barang::all();

        return view('pengeluarans.create', compact('struks', 'pegawais', 'income', 'barangList'));
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
            'items.*.nama' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        foreach ($validated['items'] as $item) {
            $kodeBarang = trim($item['nama']);
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

        foreach ($validated['items'] as $item) {
            $barang = Barang::where('kode_barang', trim($item['nama']))->first();
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
        ];

        Pengeluaran::create($pengeluaranData);

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
        $barangs = Barang::all();

        return view('struks.pengeluarans.edit', compact('pengeluaran', 'pegawais', 'barangs'));
    }

<<<<<<< HEAD
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $rules = [
            'nama_toko' => 'required|string',
            'nomor_struk' => 'required|string',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ];

        if ($pengeluaran->struk_id === null) {
            $rules['existing_items'] = 'nullable|array';
            $rules['existing_items.*.kode_barang'] = 'required|string';
            $rules['existing_items.*.jumlah'] = 'required|integer|min:1';

            $rules['new_items'] = 'nullable|array';
            $rules['new_items.*.kode_barang'] = 'required|string';
            $rules['new_items.*.jumlah'] = 'required|integer|min:1';
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
                $oldItems = is_string($pengeluaran->daftar_barang)
                    ? json_decode($pengeluaran->daftar_barang, true)
                    : ($pengeluaran->daftar_barang ?? []);

                $combinedItems = [];

                if ($request->has('existing_items')) {
                    foreach ($request->existing_items as $item) {
                        $combinedItems[] = [
                            'kode_barang' => $item['kode_barang'],
                            'jumlah' => $item['jumlah'],
                        ];
                    }
                }

                if ($request->has('new_items')) {
                    foreach ($request->new_items as $item) {
                        $combinedItems[] = [
                            'kode_barang' => $item['kode_barang'],
                            'jumlah' => $item['jumlah'],
                        ];
                    }
                }

                $jumlahItem = 0;
                $finalItems = [];

                foreach ($combinedItems as $item) {
                    $kode = $item['kode_barang'];
                    $jumlahBaru = $item['jumlah'];
                    $barang = Barang::where('kode_barang', $kode)->first();

                    if (!$barang) {
                        throw new \Exception("Barang dengan kode {$kode} tidak ditemukan.");
                    }

                    $jumlahLama = 0;
                    foreach ($oldItems as $old) {
                        $kodeLama = $old['kode_barang'] ?? $old['nama'];
                        if ($kodeLama === $kode) {
                            $jumlahLama = $old['jumlah'];
                            break;
=======
   public function update(Request $request, Pengeluaran $pengeluaran)
{
    $rules = [
        'nama_toko' => 'required|string',
        'nomor_struk' => 'required|string',
        'pegawai_id' => 'required|exists:pegawais,id',
        'tanggal' => 'required|date',
        'keterangan' => 'nullable|string',
    ];

    // Jika data barang dikirim (tanpa tergantung struk_id)
    if ($request->has('existing_items') || $request->has('new_items')) {
        $rules += [
            'existing_items' => 'sometimes|array',
            'existing_items.*.kode_barang' => 'required|string',
            'existing_items.*.jumlah' => 'required|integer|min:1',

            'new_items' => 'sometimes|array',
            'new_items.*.kode_barang' => 'required|string|exists:master_barangs,kode_barang',
            'new_items.*.jumlah' => 'required|integer|min:1',
        ];
    }

    // Bersihkan dan rapikan new_items
    $request->merge([
        'new_items' => collect($request->input('new_items', []))
            ->filter(fn($item) => isset($item['kode_barang']))
            ->map(function ($item) {
                $item['kode_barang'] = trim($item['kode_barang']);
                return $item;
            })->values()->toArray(),
    ]);

    // Validasi request
    $validated = $request->validate($rules);

    try {
        DB::transaction(function () use ($request, $validated, $pengeluaran) {
            $updateData = [
                'nama_toko' => $validated['nama_toko'],
                'nomor_struk' => $validated['nomor_struk'],
                'pegawai_id' => $validated['pegawai_id'],
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            // Jika update daftar barang (hanya jika tidak terikat struk)
            if ($pengeluaran->struk_id === null) {
                $combinedItems = [];

                // Handle existing_items
                if (isset($validated['existing_items'])) {
                    foreach ($validated['existing_items'] as $item) {
                        $barang = Barang::where('kode_barang', $item['kode_barang'])->first();
                        if (!$barang) {
                            throw new \Exception("Barang dengan kode {$item['kode_barang']} tidak ditemukan");
>>>>>>> 13e404229dad74e456a6a75e5e38e7ec08d9399e
                        }

                        if ($barang->jumlah < $item['jumlah']) {
                            throw new \Exception("Stok tidak mencukupi untuk {$barang->nama_barang}. Tersedia: {$barang->jumlah}, diminta: {$item['jumlah']}");
                        }

                        $barang->jumlah -= $item['jumlah'];
                        $barang->save();

                        $combinedItems[] = [
                            'nama' => $barang->nama_barang,
                            'kode_barang' => $item['kode_barang'],
                            'jumlah' => $item['jumlah'],
                        ];
                    }
<<<<<<< HEAD

                    $selisih = $jumlahBaru - $jumlahLama;
                    if ($selisih > 0 && $barang->jumlah < $selisih) {
                        throw new \Exception("Stok tidak cukup untuk barang {$barang->nama_barang}. Sisa stok: {$barang->jumlah}, diminta tambahan: {$selisih}");
                    }

                    $barang->jumlah -= $selisih;
                    $barang->save();

                    $finalItems[] = [
                        'kode_barang' => $kode,
                        'jumlah' => $jumlahBaru,
                        'nama' => $barang->nama_barang
                    ];

                    $jumlahItem += $jumlahBaru;
                }

                $updateData['daftar_barang'] = json_encode($finalItems);
                $updateData['jumlah_item'] = $jumlahItem;
=======
                }

                // Handle new_items
                if (isset($validated['new_items'])) {
                    foreach ($validated['new_items'] as $item) {
                        $barang = Barang::where('kode_barang', $item['kode_barang'])->first();
                        if (!$barang) {
                            throw new \Exception("Barang dengan kode {$item['kode_barang']} tidak ditemukan");
                        }

                        if ($barang->jumlah < $item['jumlah']) {
                            throw new \Exception("Stok tidak mencukupi untuk {$barang->nama_barang}. Tersedia: {$barang->jumlah}, diminta: {$item['jumlah']}");
                        }

                        $barang->jumlah -= $item['jumlah'];
                        $barang->save();

                        $combinedItems[] = [
                            'nama' => $barang->nama_barang,
                            'kode_barang' => $item['kode_barang'],
                            'jumlah' => $item['jumlah'],
                        ];
                    }
                }

                // Simpan barang ke pengeluaran
                $updateData['daftar_barang'] = json_encode($combinedItems);
                $updateData['jumlah_item'] = array_sum(array_column($combinedItems, 'jumlah'));
>>>>>>> 13e404229dad74e456a6a75e5e38e7ec08d9399e
            }

            $pengeluaran->update($updateData);
        });

<<<<<<< HEAD
        // Redirect dengan parameter pencarian yang sama untuk mempertahankan state
        $search = $request->query('search', '');
        return redirect()->route('pengeluarans.index', ['search' => $search])->with('updated', 'Pengeluaran berhasil diperbarui dan stok diperbarui.');
    }
=======
        return redirect()->route('pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil diperbarui dan stok diperbarui.');
    } catch (\Throwable $e) {
        return back()->withErrors(['db_error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}
>>>>>>> 13e404229dad74e456a6a75e5e38e7ec08d9399e



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
}