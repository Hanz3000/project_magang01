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
    // Tampilkan semua struk
    public function index(Request $request)
    {
        $query = Struk::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(nama_toko) like ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(nomor_struk) like ?', ["%{$search}%"]);
        }

        $struks = $query->latest()->get();

        return view('struks.index', compact('struks'));
    }

    // Tampilkan form tambah
    public function create()
    {
        return view('struks.create');
    }

    // Simpan struk baru
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

        $fotoPath = null;
        if ($request->hasFile('foto_struk')) {
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
        }

        Struk::create([
            'nama_toko' => $request->nama_toko,
            'nomor_struk' => $request->nomor_struk,
            'tanggal_struk' => $request->tanggal_struk,
            'items' => json_encode($request->items),
            'total_harga' => $request->total_harga,
            'foto_struk' => $fotoPath
        ]);

        return redirect()->route('struks.index')->with('success', 'Struk berhasil disimpan!');
    }

    // Tampilkan form edit
    public function edit(Struk $struk)
    {
        $struk->items = json_decode($struk->items);
        return view('struks.edit', compact('struk'));
    }

    // Update struk
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

        // Ganti foto jika ada yang baru
        if ($request->hasFile('foto_struk')) {
            if ($struk->foto_struk) {
                Storage::disk('public')->delete($struk->foto_struk);
            }
            $struk->foto_struk = $request->file('foto_struk')->store('struk_foto', 'public');
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

        $validated = $request->validate([
            'nama' => 'required|array',
            'nama.*' => 'required|string',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
        ]);

        $newItems = [];

        foreach ($validated['nama'] as $i => $nama) {
            $jumlah = $validated['jumlah'][$i];
            $harga = $validated['harga'][$i];

            if (isset($request->item_index[$i])) {
                // Perbarui item lama
                $index = $request->item_index[$i];
                $existingItems[$index] = [
                    'nama' => $nama,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                ];
            } else {
                // Tambahkan item baru
                if ($nama !== null && $jumlah > 0 && $harga >= 0) {
                    $newItems[] = [
                        'nama' => $nama,
                        'jumlah' => $jumlah,
                        'harga' => $harga,
                    ];
                }
            }
        }

        // Gabungkan data
        $finalItems = array_values(array_merge($existingItems, $newItems));

        $total = collect($finalItems)->sum(function ($item) {
            return $item['jumlah'] * $item['harga'];
        });

        $struk->items = json_encode($finalItems);
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
}
