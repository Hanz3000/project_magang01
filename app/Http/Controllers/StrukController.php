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
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_toko) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(nomor_struk) like ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(items::text) like ?', ["%{$search}%"]);
            });
        }

        $struks = $query->latest()->paginate(10);
    $barangList = Barang::all()->keyBy('kode_barang');
    
    // Transform items untuk menyertakan nama barang
    $struks->getCollection()->transform(function($struk) use ($barangList) {
        $items = json_decode($struk->items, true);
        $items = array_map(function($item) use ($barangList) {
            $item['nama_barang'] = $barangList[$item['nama']]->nama_barang ?? $item['nama'];
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
            'nomor_struk' => 'required|string|max:255',
            'tanggal_struk' => 'required|date',
            'tanggal_keluar' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'foto_struk' => 'nullable|image|max:2048'
        ]);

        // Upload foto struk
        $fotoFilename = null;
        if ($request->hasFile('foto_struk')) {
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
            $fotoFilename = basename($fotoPath);
        }

        // Validasi dan kurangi stok master barang
        foreach ($validatedData['items'] as $item) {
            $kodeBarang = $item['nama']; // nama = kode_barang
            $jumlah = $item['jumlah'];

            $barang = Barang::where('kode_barang', $kodeBarang)->first();
            if (!$barang) {
                return back()->withErrors(['Barang dengan kode ' . $kodeBarang . ' tidak ditemukan.']);
            }

            if ($barang->jumlah < $jumlah) {
                return back()->withErrors(['Stok barang "' . $barang->nama_barang . '" tidak mencukupi. Stok tersedia: ' . $barang->jumlah]);
            }

            // Kurangi stok
            $barang->jumlah -= $jumlah;
            $barang->save();
        }

        // Simpan struk
        Struk::create([
            'nama_toko'      => $validatedData['nama_toko'],
            'nomor_struk'    => $validatedData['nomor_struk'],
            'tanggal_struk'  => $validatedData['tanggal_struk'],
            'tanggal_keluar' => $validatedData['tanggal_keluar'] ?? null,
            'items'          => json_encode($validatedData['items']),
            'total_harga'    => $validatedData['total_harga'],
            'foto_struk'     => $fotoFilename
        ]);

        return redirect()->route('struks.index')->with('created', 'Struk berhasil disimpan dan stok telah dikurangi.');
    }



    public function edit(Struk $struk)
    {
        $struk->items = json_decode($struk->items);
        $barangList = Barang::all();
        return view('struks.edit', compact('struk', 'barangList'));
    }

    public function update(Request $request, Struk $struk)
    {
        $validatedData = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'nomor_struk' => 'required|string|max:255',
            'tanggal_struk' => 'required|date',
            'tanggal_keluar' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'foto_struk' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto_struk')) {
            if ($struk->foto_struk) {
                Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
            }
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
            $validatedData['foto_struk'] = basename($fotoPath);
        } else {
            $validatedData['foto_struk'] = $struk->foto_struk;
        }

        // Di method update()
DB::beginTransaction();
try {
    // 1. Kembalikan stok dari item lama
    $oldItems = json_decode($struk->items, true);
    foreach ($oldItems as $item) {
        $barang = Barang::where('kode_barang', $item['nama'])->first();
        if ($barang) {
            $barang->increment('jumlah', $item['jumlah']);
        }
    }

    // 2. Kurangi stok baru
    foreach ($validatedData['items'] as $item) {
        $barang = Barang::where('kode_barang', $item['nama'])->first();
        if ($barang) {
            if ($barang->jumlah < $item['jumlah']) {
                DB::rollBack();
                return back()->withErrors(['Stok barang "' . $barang->nama_barang . '" tidak mencukupi. Stok tersedia: ' . $barang->jumlah]);
            }
            $barang->decrement('jumlah', $item['jumlah']);
        }
    }

    // 3. Update struk
    $struk->update([
        'nama_toko' => $validatedData['nama_toko'],
        'nomor_struk' => $validatedData['nomor_struk'],
        'tanggal_struk' => $validatedData['tanggal_struk'],
        'tanggal_keluar' => $validatedData['tanggal_keluar'] ?? null,
        'items' => json_encode($validatedData['items']),
        'total_harga' => $validatedData['total_harga'],
        'foto_struk' => $validatedData['foto_struk']
    ]);

    DB::commit();
    return redirect()->route('struks.index')->with('updated', 'Struk berhasil diperbarui!');
} catch (\Exception $e) {
    DB::rollBack();
    return back()->withErrors('Gagal mengupdate struk: ' . $e->getMessage());
}
    }



    public function destroy(Struk $struk)
    {
        if ($struk->foto_struk) {
            Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
        }
        $struk->delete();
        return redirect()->route('struks.index')->with('deleted', 'Struk berhasil dihapus!');

    
        DB::beginTransaction();
try {
    // Kembalikan stok barang
    $items = json_decode($struk->items, true);
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
    return redirect()->route('struks.index')->with('deleted', 'Struk berhasil dihapus!');
} catch (\Exception $e) {
    DB::rollBack();
    return back()->withErrors('Gagal menghapus struk: ' . $e->getMessage());
}
    }

    public function show(Struk $struk)
    {
        $struk->items = json_decode($struk->items);
        return view('struks.show', compact('struk'));
    }

    public function exportExcel()
    {
        $struks = Struk::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Nama Toko', 'Nomor Struk', 'Tanggal', 'Items', 'Total Harga'], NULL, 'A1');

        $row = 2;
        foreach ($struks as $struk) {
            $items = collect(json_decode($struk->items))->map(fn($item) => "{$item->nama} ({$item->jumlah} x {$item->harga})")->implode(', ');
            $sheet->fromArray([$struk->id, $struk->nama_toko, $struk->nomor_struk, $struk->tanggal_struk, $items, $struk->total_harga], NULL, "A$row");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="data_struks.xlsx"');

        $writer->save('php://output');
        exit;
    }

    public function exportCSV()
    {
        $struks = Struk::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Nama Toko', 'Nomor Struk', 'Tanggal', 'Items', 'Total Harga'], NULL, 'A1');

        $row = 2;
        foreach ($struks as $struk) {
            $items = collect(json_decode($struk->items))->map(fn($item) => "{$item->nama} ({$item->jumlah} x {$item->harga})")->implode(', ');
            $sheet->fromArray([$struk->id, $struk->nama_toko, $struk->nomor_struk, $struk->tanggal_struk, $items, $struk->total_harga], NULL, "A$row");
            $row++;
        }

        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="data_struks.csv"');
        $writer->save('php://output');
        exit;
    }

    public function updateItems(Request $request, $id)
{
    $struk = Struk::findOrFail($id);
    
    $request->validate([
        'nama.*' => 'required|string|max:255',
        'jumlah.*' => 'required|integer|min:1',
        'harga.*' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        // 1. Kembalikan stok lama
        $oldItems = json_decode($struk->items, true);
        foreach ($oldItems as $item) {
            $barang = Barang::where('kode_barang', $item['nama'])->first();
            if ($barang) {
                $barang->increment('jumlah', $item['jumlah']);
            }
        }

        // 2. Proses item baru
        $namaArr = $request->input('nama', []);
        $jumlahArr = $request->input('jumlah', []);
        $hargaArr = $request->input('harga', []);

        $items = [];
        foreach ($namaArr as $i => $nama) {
            $kodeBarang = $nama;
            $jumlah = (int) ($jumlahArr[$i] ?? 0);
            $harga = (float) ($hargaArr[$i] ?? 0);

            $barang = Barang::where('kode_barang', $kodeBarang)->first();
            if (!$barang) {
                DB::rollBack();
                return back()->withErrors(['Barang dengan kode ' . $kodeBarang . ' tidak ditemukan.']);
            }

            if ($barang->jumlah < $jumlah) {
                DB::rollBack();
                return back()->withErrors(['Stok barang "' . $barang->nama_barang . '" tidak mencukupi. Stok tersedia: ' . $barang->jumlah]);
            }

            $barang->decrement('jumlah', $jumlah);

            $items[] = [
                'nama' => $kodeBarang,
                'jumlah' => $jumlah,
                'harga' => $harga,
            ];
        }

        $total = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);

        $struk->items = json_encode($items);
        $struk->total_harga = $total;
        $struk->save();

        DB::commit();
        return redirect()->route('struks.index')->with('success', 'Item berhasil diperbarui!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors('Gagal mengupdate item: ' . $e->getMessage());
    }
}

    public function addItem(Request $request, $id)
    {
        $struk = Struk::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ]);

        $items = json_decode($struk->items, true) ?? [];

        $items[] = $request->only(['nama', 'jumlah', 'harga']);
        $struk->items = json_encode($items);
        $struk->total_harga = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);

        $struk->save();
        return redirect()->route('struks.index')->with('success', 'Item baru berhasil ditambahkan.');
    }

    public function deleteItem($id, $index)
{
    $struk = Struk::findOrFail($id);
    $items = json_decode($struk->items, true);

    if (!isset($items[$index])) {
        return redirect()->route('struks.index')->with('error', 'Item tidak ditemukan.');
    }

    DB::beginTransaction();
    try {
        // Kembalikan stok barang
        $deletedItem = $items[$index];
        $barang = Barang::where('kode_barang', $deletedItem['nama'])->first();
        if ($barang) {
            $barang->increment('jumlah', $deletedItem['jumlah']);
        }

        unset($items[$index]);
        $items = array_values($items);
        
        $struk->items = json_encode($items);
        $struk->total_harga = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);
        $struk->save();

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
        'selected_ids.*' => 'exists:struks,id'
    ]);

    DB::beginTransaction();
    try {
        $struks = Struk::whereIn('id', $selectedIds)->get();
        
        // Kembalikan stok untuk semua struk yang akan dihapus
        foreach ($struks as $struk) {
            $items = json_decode($struk->items, true);
            foreach ($items as $item) {
                $barang = Barang::where('kode_barang', $item['nama'])->first();
                if ($barang) {
                    $barang->increment('jumlah', $item['jumlah']);
                }
            }
            
            // Hapus foto struk jika ada
            if ($struk->foto_struk) {
                Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
            }
        }

        // Hapus struk
        Struk::whereIn('id', $selectedIds)->delete();

        DB::commit();
        return redirect()->route('struks.index')
            ->with('success', count($selectedIds) . ' struk berhasil dihapus');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors('Gagal menghapus struk: ' . $e->getMessage());
    }
}


    public function getItems(Struk $struk)
    {
        $items = json_decode($struk->items, true);
        return response()->json([
            'items' => $items,
            'foto_struk' => $struk->foto_struk
        ]);
    }
}