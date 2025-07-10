<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Facades\DB;

class StrukController extends Controller
{
    public function index(Request $request)
    {
        $query = Struk::query();

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_toko) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(nomor_struk) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(CAST(items AS TEXT)) LIKE ?', ["%{$search}%"]);
            });
        }

        $struks = $query->latest()->paginate(10);
        $barangList = Barang::all()->keyBy('kode_barang');

        $struks->getCollection()->transform(function ($struk) use ($barangList) {
            $items = json_decode($struk->items, true) ?? [];
            $items = array_map(function ($item) use ($barangList) {
                $item['nama_barang'] = $barangList[$item['nama']]?->nama_barang ?? $item['nama'];
                return $item;
            }, $items);
            $struk->items = json_encode($items);
            return $struk;
        });

        return view('struks.index', compact('struks', 'barangList'));
    }

    public function create()
    {
        $barangList = Barang::all();
        return view('struks.create', compact('barangList'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'nomor_struk' => 'required|string|max:255|unique:struks,nomor_struk',
            'tanggal_struk' => 'required|date',
            'tanggal_keluar' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string', // sebelumnya 'exists', sekarang hanya 'string'
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'foto_struk' => 'nullable|image|max:2048',
        ]);

        $fotoFilename = null;
        if ($request->hasFile('foto_struk')) {
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
            $fotoFilename = basename($fotoPath);
        }

        DB::beginTransaction();
        try {
            foreach ($validatedData['items'] as $item) {
                $kodeBarang = $item['nama'];
                $jumlah = $item['jumlah'];

                $barang = Barang::where('kode_barang', $kodeBarang)->first();

                if ($barang) {
                    // Barang sudah ada, update jumlah
                    $barang->jumlah += $jumlah;
                } else {
                    // Barang belum ada, buat otomatis
                    $barang = new Barang();
                    $barang->kode_barang = $kodeBarang;
                    $barang->nama_barang = $kodeBarang; // bisa ganti dengan input 'nama_barang' jika tersedia
                    $barang->kategori = 'Lainnya'; // default kategori, bisa disesuaikan
                    $barang->jumlah = $jumlah;
                }

                $barang->save();
            }

            Struk::create([
                'nama_toko' => $validatedData['nama_toko'],
                'nomor_struk' => $validatedData['nomor_struk'],
                'tanggal_struk' => $validatedData['tanggal_struk'],
                'tanggal_keluar' => $validatedData['tanggal_keluar'] ?? null,
                'items' => json_encode($validatedData['items']),
                'total_harga' => $validatedData['total_harga'],
                'foto_struk' => $fotoFilename,
            ]);

            DB::commit();
            return redirect()->route('struks.index')->with('success', 'Struk berhasil disimpan dan stok diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($fotoFilename) {
                Storage::disk('public')->delete('struk_foto/' . $fotoFilename);
            }
            return back()->withErrors('Gagal menyimpan struk: ' . $e->getMessage())->withInput();
        }
    }


    public function edit(Struk $struk)
    {
        $struk->items = json_decode($struk->items, true) ?? [];
        $barangList = Barang::all();
        return view('struks.edit', compact('struk', 'barangList'));
    }

    public function update(Request $request, Struk $struk)
    {
        $validatedData = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'nomor_struk' => 'required|string|max:255|unique:struks,nomor_struk,' . $struk->id,
            'tanggal_struk' => 'required|date',
            'tanggal_keluar' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|exists:master_barangs,kode_barang',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'foto_struk' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $oldItems = json_decode($struk->items, true) ?? [];
            foreach ($oldItems as $item) {
                $barang = Barang::where('kode_barang', $item['nama'])->first();
                if ($barang) {
                    $barang->jumlah -= $item['jumlah'];
                    $barang->save();
                }
            }

            if ($request->hasFile('foto_struk')) {
                if ($struk->foto_struk) {
                    Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
                }
                $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
                $validatedData['foto_struk'] = basename($fotoPath);
            } else {
                $validatedData['foto_struk'] = $struk->foto_struk;
            }

            foreach ($validatedData['items'] as $item) {
                $barang = Barang::where('kode_barang', $item['nama'])->firstOrFail();
                $barang->jumlah += $item['jumlah'];
                $barang->save();
            }

            $struk->update([
                'nama_toko' => $validatedData['nama_toko'],
                'nomor_struk' => $validatedData['nomor_struk'],
                'tanggal_struk' => $validatedData['tanggal_struk'],
                'tanggal_keluar' => $validatedData['tanggal_keluar'] ?? null,
                'items' => json_encode($validatedData['items']),
                'total_harga' => $validatedData['total_harga'],
                'foto_struk' => $validatedData['foto_struk'],
            ]);

            DB::commit();
            return redirect()->route('struks.index')->with('success', 'Struk berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->hasFile('foto_struk')) {
                Storage::disk('public')->delete('struk_foto/' . basename($request->file('foto_struk')->store('struk_foto', 'public')));
            }
            return back()->withErrors('Gagal mengupdate struk: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Struk $struk)
    {
        DB::beginTransaction();
        try {
            $items = json_decode($struk->items, true) ?? [];
            foreach ($items as $item) {
                $barang = Barang::where('kode_barang', $item['nama'])->first();
                if ($barang) {
                    $barang->increment('jumlah', $item['jumlah']);
                }
            }

            if ($struk->foto_struk) {
                Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
            }

            $struk->delete();

            DB::commit();
            return redirect()->route('struks.index')->with('success', 'Struk berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus struk: ' . $e->getMessage());
        }
    }

    public function show(Struk $struk)
    {
        $struk->items = json_decode($struk->items, true) ?? [];
        $masterBarang = Barang::all()->keyBy('kode_barang');
        return view('struks.show', compact('struk', 'masterBarang'));
    }

    public function exportExcel()
    {
        $struks = Struk::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Nama Toko', 'Nomor Struk', 'Tanggal', 'Items', 'Total Harga'], NULL, 'A1');

        $row = 2;
        foreach ($struks as $struk) {
            $items = json_decode($struk->items, true) ?? [];
            $itemsString = collect($items)->map(fn($item) => "{$item['nama']} ({$item['jumlah']} x {$item['harga']})")->implode(', ');
            $sheet->fromArray([
                $struk->id,
                $struk->nama_toko,
                $struk->nomor_struk,
                $struk->tanggal_struk,
                $itemsString,
                $struk->total_harga
            ], NULL, "A$row");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="data_struks_' . date('Ymd_His') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportCsv()
    {
        $struks = Struk::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Nama Toko', 'Nomor Struk', 'Tanggal', 'Items', 'Total Harga'], NULL, 'A1');

        $row = 2;
        foreach ($struks as $struk) {
            $items = json_decode($struk->items, true) ?? [];
            $itemsString = collect($items)->map(fn($item) => "{$item['nama']} ({$item['jumlah']} x {$item['harga']})")->implode(', ');
            $sheet->fromArray([
                $struk->id,
                $struk->nama_toko,
                $struk->nomor_struk,
                $struk->tanggal_struk,
                $itemsString,
                $struk->total_harga
            ], NULL, "A$row");
            $row++;
        }

        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="data_struks_' . date('Ymd_His') . '.csv"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function updateItems(Request $request, $id)
    {
        $struk = Struk::findOrFail($id);

        $request->validate([
            'nama.*' => 'required|exists:master_barangs,kode_barang',
            'jumlah.*' => 'required|integer|min:1',
            'harga.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $oldItems = json_decode($struk->items, true) ?? [];
            foreach ($oldItems as $item) {
                $barang = Barang::where('kode_barang', $item['nama'])->first();
                if ($barang) {
                    $barang->jumlah -= $item['jumlah'];
                    $barang->save();
                }
            }

            $namaArr = $request->input('nama', []);
            $jumlahArr = $request->input('jumlah', []);
            $hargaArr = $request->input('harga', []);

            $items = [];
            foreach ($namaArr as $i => $nama) {
                $jumlah = (int) ($jumlahArr[$i] ?? 0);
                $harga = (float) ($hargaArr[$i] ?? 0);

                $barang = Barang::where('kode_barang', $nama)->firstOrFail();
                $barang->jumlah += $jumlah;
                $barang->save();

                $items[] = [
                    'nama' => $nama,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                ];
            }

            $total = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);

            $struk->update([
                'items' => json_encode($items),
                'total_harga' => $total,
            ]);

            DB::commit();
            return redirect()->route('struks.index')->with('success', 'Item berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengupdate item: ' . $e->getMessage())->withInput();
        }
    }

    public function addItem(Request $request, $id)
    {
        $struk = Struk::findOrFail($id);

        $request->validate([
            'nama' => 'required|exists:master_barangs,kode_barang',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $items = json_decode($struk->items, true) ?? [];
            $barang = Barang::where('kode_barang', $request->nama)->firstOrFail();
            $barang->jumlah += $request->jumlah;
            $barang->save();

            $items[] = $request->only(['nama', 'jumlah', 'harga']);
            $total = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);

            $struk->update([
                'items' => json_encode($items),
                'total_harga' => $total,
            ]);

            DB::commit();
            return redirect()->route('struks.index')->with('success', 'Item baru berhasil ditambahkan dan stok telah ditambah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menambahkan item: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteItem($id, $index)
    {
        $struk = Struk::findOrFail($id);
        $items = json_decode($struk->items, true) ?? [];

        if (!isset($items[$index])) {
            return redirect()->route('struks.index')->with('error', 'Item tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $deletedItem = $items[$index];
            $barang = Barang::where('kode_barang', $deletedItem['nama'])->first();
            if ($barang) {
                $barang->increment('jumlah', $deletedItem['jumlah']);
            }

            unset($items[$index]);
            $items = array_values($items);
            $total = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);

            $struk->update([
                'items' => json_encode($items),
                'total_harga' => $total,
            ]);

            DB::commit();
            return redirect()->route('struks.index')->with('success', 'Item berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('struks.index')->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $selectedIds = $request->input('selected_ids');

        if (is_string($selectedIds)) {
            $selectedIds = explode(',', $selectedIds);
            $request->merge(['selected_ids' => $selectedIds]);
        }

        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:struks,id',
        ]);

        DB::beginTransaction();
        try {
            $struks = Struk::whereIn('id', $selectedIds)->get();

            foreach ($struks as $struk) {
                $items = json_decode($struk->items, true) ?? [];
                foreach ($items as $item) {
                    $barang = Barang::where('kode_barang', $item['nama'])->first();
                    if ($barang) {
                        $barang->increment('jumlah', $item['jumlah']);
                    }
                }

                if ($struk->foto_struk) {
                    Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
                }
            }

            Struk::whereIn('id', $selectedIds)->delete();

            DB::commit();
            return redirect()->route('struks.index')->with('success', count($selectedIds) . ' struk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus struk: ' . $e->getMessage());
        }
    }

    public function getItems(Struk $struk)
    {
        $items = json_decode($struk->items, true) ?? [];
        $barangList = Barang::all()->keyBy('kode_barang');

        $items = array_map(function ($item) use ($barangList) {
            $item['nama_barang'] = $barangList[$item['nama']]?->nama_barang ?? $item['nama'];
            return $item;
        }, $items);

        return response()->json([
            'items' => $items,
            'foto_struk' => $struk->foto_struk,
        ]);
    }
}
