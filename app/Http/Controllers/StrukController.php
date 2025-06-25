<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Barang;
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

        $struks = $query->latest()->get();
        return view('struks.index', compact('struks'));
    }

    public function create()
    {
        $barangList = Barang::all();
        return view('struks.create', compact('barangList'));
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
        ]);

        $fotoPath = $request->hasFile('foto_struk')
            ? $request->file('foto_struk')->store('struk_foto', 'public')
            : null;

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

    public function edit(Struk $struk)
    {
        $struk->items = json_decode($struk->items);
        $barangList = Barang::all();
        return view('struks.edit', compact('struk', 'barangList'));
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
        ]);

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

    public function destroy(Struk $struk)
    {
        if ($struk->foto_struk) {
            Storage::disk('public')->delete($struk->foto_struk);
        }
        $struk->delete();
        return redirect()->route('struks.index')->with('success', 'Struk berhasil dihapus!');
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
                $index = $request->item_index[$i];
                $existingItems[$index] = compact('nama', 'jumlah', 'harga');
            } else {
                $newItems[] = compact('nama', 'jumlah', 'harga');
            }
        }

        $finalItems = array_values(array_merge($existingItems, $newItems));
        $total = collect($finalItems)->sum(fn($item) => $item['jumlah'] * $item['harga']);

        $struk->items = json_encode($finalItems);
        $struk->total_harga = $total;
        $struk->save();

        return redirect()->route('struks.index')->with('success', 'Item berhasil disimpan!');
    }

    public function addItem(Request $request, $id)
    {
        $struk = Struk::findOrFail($id);
        $items = json_decode($struk->items, true);

        $items[] = $request->only(['nama', 'jumlah', 'harga']);
        $struk->items = json_encode($items);
        $struk->total_harga = collect($items)->sum(fn($item) => $item['jumlah'] * $item['harga']);

        $struk->save();
        return redirect()->route('struks.index')->with('success', 'Item baru berhasil ditambahkan.');
    }
}