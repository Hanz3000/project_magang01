<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class StrukController extends Controller
{
    public function index(Request $request)
    {
        $query = Struk::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(nama_toko) like ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(nomor_struk) like ?', ["%{$search}%"]);
        }

        $struks = $query->latest()->paginate(10); // Gunakan pagination

        return view('struks.index', compact('struks'));
    }


    public function create()
    {
        return view('struks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required',
            'nomor_struk' => 'required',
            'tanggal_struk' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric',
            'foto_struk' => 'nullable|image|max:2048'
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'nomor_struk.required' => 'Nomor struk wajib diisi.',
            'tanggal_struk.required' => 'Tanggal struk wajib diisi.',
            'items.required' => 'Minimal 1 item harus diisi.',
            'items.*.nama.required' => 'Nama barang harus diisi.',
            'items.*.jumlah.required' => 'Jumlah barang harus diisi.',
            'items.*.harga.required' => 'Harga barang harus diisi.',
            'total_harga.required' => 'Total harga wajib diisi.',
        ]);

        $fotoFilename = null;
        if ($request->hasFile('foto_struk')) {
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
            $fotoFilename = basename($fotoPath);
        }

        Struk::create([
            'nama_toko' => $request->nama_toko,
            'nomor_struk' => $request->nomor_struk,
            'tanggal_struk' => $request->tanggal_struk,
            'items' => json_encode($request->items),
            'total_harga' => $request->total_harga,
            'foto_struk' => $fotoFilename
        ]);

        return redirect()->route('struks.index')->with('success', 'Struk berhasil disimpan!');
    }

    public function edit(Struk $struk)
    {
        $struk->items = json_decode($struk->items);
        return view('struks.edit', compact('struk'));
    }

    public function update(Request $request, Struk $struk)
    {
        $request->validate([
            'nama_toko' => 'required',
            'nomor_struk' => 'required',
            'tanggal_struk' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric',
            'foto_struk' => 'nullable|image|max:2048'
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'nomor_struk.required' => 'Nomor struk wajib diisi.',
            'tanggal_struk.required' => 'Tanggal struk wajib diisi.',
            'items.required' => 'Minimal 1 item harus diisi.',
            'items.*.nama.required' => 'Nama barang harus diisi.',
            'items.*.jumlah.required' => 'Jumlah barang harus diisi.',
            'items.*.harga.required' => 'Harga barang harus diisi.',
            'total_harga.required' => 'Total harga wajib diisi.',
        ]);

        if ($request->hasFile('foto_struk')) {
            if ($struk->foto_struk) {
                Storage::disk('public')->delete('struk_foto/' . $struk->foto_struk);
            }
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
            $struk->foto_struk = basename($fotoPath);
        }

        $struk->update([
            'nama_toko' => $request->nama_toko,
            'nomor_struk' => $request->nomor_struk,
            'tanggal_struk' => $request->tanggal_struk,
            'items' => json_encode($request->items),
            'total_harga' => $request->total_harga,
            'foto_struk' => $struk->foto_struk,
        ]);

        return redirect()->route('struks.index')->with('success', 'Struk berhasil diperbarui!');
    }

    // (functio definitions for destroy, show, exportExcel, exportCSV, updateItems, addItem, deleteItem stay the same)


    // Hapus struk
    public function destroy(Struk $struk)
    {
        if ($struk->foto_struk) {
            Storage::disk('public')->delete($struk->foto_struk);
        }

        $struk->delete();
        return redirect()->route('struks.index')->with('success', 'Struk berhasil dihapus!');
    }

    // (Opsional) Menampilkan 1 struk saja
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

        // Header
        $sheet->fromArray([
            'ID',
            'Nama Toko',
            'Nomor Struk',
            'Tanggal',
            'Items',
            'Total Harga'
        ], NULL, 'A1');

        // Data
        $row = 2;
        foreach ($struks as $struk) {
            $items = collect(json_decode($struk->items))->map(function ($item) {
                return "{$item->nama} ({$item->jumlah} x {$item->harga})";
            })->implode(', ');

            $sheet->fromArray([
                $struk->id,
                $struk->nama_toko,
                $struk->nomor_struk,
                $struk->tanggal_struk,
                $items,
                $struk->total_harga,
            ], NULL, "A$row");

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_struks.xlsx';

        // Output response
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }

    public function exportCSV()
    {
        $struks = Struk::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->fromArray([
            'ID',
            'Nama Toko',
            'Nomor Struk',
            'Tanggal',
            'Items',
            'Total Harga'
        ], NULL, 'A1');

        // Data
        $row = 2;
        foreach ($struks as $struk) {
            $items = collect(json_decode($struk->items))->map(function ($item) {
                return "{$item->nama} ({$item->jumlah} x {$item->harga})";
            })->implode(', ');

            $sheet->fromArray([
                $struk->id,
                $struk->nama_toko,
                $struk->nomor_struk,
                $struk->tanggal_struk,
                $items,
                $struk->total_harga,
            ], NULL, "A$row");

            $row++;
        }

        $writer = new Csv($spreadsheet);
        $filename = 'data_struks.csv';

        // Output response
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save("php://output");
        exit;
    }



    public function updateItems(Request $request, $id)
    {
        $struk = Struk::findOrFail($id);
        $existingItems = json_decode($struk->items, true) ?? [];

        $namaArr = $request->input('nama', []);
        $jumlahArr = $request->input('jumlah', []);
        $hargaArr = $request->input('harga', []);
        $indexArr = $request->input('item_index', []);

        $finalItems = [];

        foreach ($namaArr as $i => $nama) {
            $jumlah = $jumlahArr[$i] ?? null;
            $harga = $hargaArr[$i] ?? null;

            // Lewati jika salah satu kolom kosong (baris tidak valid)
            if ($nama === null || $nama === '' || $jumlah === null || $harga === null) {
                continue;
            }

            // Validasi manual tiap baris
            if (!is_numeric($jumlah) || $jumlah < 1 || !is_numeric($harga) || $harga < 0) {
                return back()->withErrors(['items' => 'Semua item harus memiliki jumlah >= 1 dan harga >= 0'])->withInput();
            }

            $item = [
                'nama' => $nama,
                'jumlah' => (int) $jumlah,
                'harga' => (float) $harga,
            ];

            // Jika ada item_index berarti item lama, simpan di posisi lama
            if (isset($indexArr[$i])) {
                $existingItems[$indexArr[$i]] = $item;
            } else {
                $finalItems[] = $item;
            }
        }

        // Gabungkan item lama yang sudah di-update dan item baru
        $mergedItems = array_merge($existingItems, $finalItems);
        $mergedItems = array_values($mergedItems); // reset index

        $total = collect($mergedItems)->sum(function ($item) {
            return $item['jumlah'] * $item['harga'];
        });

        $struk->items = json_encode($mergedItems);
        $struk->total_harga = $total;
        $struk->save();

        return redirect()->route('struks.index')->with('success', 'Item berhasil disimpan!');
    }




    public function addItem(Request $request, $id)
    {
        $struk = Struk::findOrFail($id);
        $items = json_decode($struk->items, true);

        $items[] = [
            'nama' => $request->input('nama'),
            'jumlah' => $request->input('jumlah'),
            'harga' => $request->input('harga'),
        ];

        $struk->items = json_encode($items);
        $struk->total_harga = collect($items)->sum(function ($item) {
            return $item['jumlah'] * $item['harga'];
        });

        $struk->save();

        return redirect()->route('struks.index')->with('success', 'Item baru berhasil ditambahkan.');
    }

    public function deleteItem($id, $index)
    {
        $struk = Struk::findOrFail($id);
        $items = json_decode($struk->items, true);

        // Hapus item dengan index tertentu
        if (isset($items[$index])) {
            unset($items[$index]);
            $items = array_values($items); // Reset indeks array
            $struk->items = json_encode($items);
            $struk->save();
        }

        return redirect()->route('struks.index', ['edit' => $struk->id])
            ->with('success', 'Item berhasil dihapus.');
    }
}
