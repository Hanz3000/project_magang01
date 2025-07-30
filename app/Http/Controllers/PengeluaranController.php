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
        $query = Pengeluaran::with(['pegawai', 'income']);

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_toko', 'like', '%' . $request->search . '%')
                ->orWhere('nomor_struk', 'like', '%' . $request->search . '%')
                ->orWhereHas('pegawai', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                });
        }

        $pengeluarans = $query->latest()->paginate(10);
        $barangs = Barang::pluck('nama_barang', 'kode_barang');

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
        $income = $request->has('income_id') ? Struk::find($request->income_id) : null;
        $barangList = Barang::all();

        // Generate preview of the next SPK number
        $nextSpkNumber = $this->generateNomorStruk();

        return view('pengeluarans.create', compact('struks', 'pegawais', 'income', 'barangList', 'nextSpkNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        // Generate nomor struk otomatis
        $validated['nomor_struk'] = $this->generateNomorStruk();

        // Cek duplikasi nomor struk
        $existing = Pengeluaran::where('nomor_struk', $validated['nomor_struk'])->first();
        if ($existing) {
            return back()->withErrors(['Nomor struk sudah digunakan. Silahkan coba lagi.']);
        }

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
        $datePart = $today->format('d/m/y'); // Format: 28/08/25
        
        // Cari nomor terakhir hari ini
        $lastPengeluaran = Pengeluaran::where('nomor_struk', 'like', 'spk/' . $today->format('d/m/y') . '%')
                                    ->orderBy('nomor_struk', 'desc')
                                    ->first();
        
        $sequence = 1;
        if ($lastPengeluaran) {
            // Extract sequence number dari nomor struk terakhir
            $lastNumber = substr($lastPengeluaran->nomor_struk, -5);
            $sequence = intval($lastNumber) + 1;
        }
        
        $sequencePart = str_pad($sequence, 5, '0', STR_PAD_LEFT);
        
        return 'spk/' . $datePart . $sequencePart;
    }
}