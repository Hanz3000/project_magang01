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
    public function index()
    {
        $struks = Struk::latest()->get();
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
        return $struk;
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
}
