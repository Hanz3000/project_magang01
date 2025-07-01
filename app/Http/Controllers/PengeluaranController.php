<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;


class PengeluaranController extends Controller
{
    protected const ITEMS_PER_PAGE = 10;
    protected const BUKTI_PEMBAYARAN_DISK = 'public';
    protected const BUKTI_PEMBAYARAN_FOLDER = 'bukti-pengeluaran';

    public function index(Request $request)
    {
        $search = $request->input('search');

        $pengeluarans = Pengeluaran::query()
            ->with('pegawai')
            ->when($search, function ($query, $search) {
                $query->where('nama_toko', 'like', "%{$search}%")
                    ->orWhere('nomor_struk', 'like', "%{$search}%")
                    ->orWhereJsonContains('daftar_barang', ['nama' => $search]);
            })
            ->orderBy('tanggal', 'desc') // Newest first by date
            ->paginate(self::ITEMS_PER_PAGE);

        $totalPengeluaran = Pengeluaran::sum('total');

        return view('struks.pengeluaran.index', compact('pengeluarans', 'search', 'totalPengeluaran'));
    }

    public function create()
    {
        $pegawais = Pegawai::all();
        $struks = \App\Models\Struk::all(); // ambil semua pemasukan (struk)
        return view('struks.pengeluaran.create', compact('pegawais', 'struks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'struk_id' => 'required|exists:struks,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
        ]);

        $struk = \App\Models\Struk::findOrFail($validated['struk_id']);

        $pengeluaran = Pengeluaran::create([
            'nama_toko' => $struk->nama_toko,
            'nomor_struk' => $struk->nomor_struk,
            'tanggal' => $validated['tanggal'],
            'pegawai_id' => $validated['pegawai_id'],
            'daftar_barang' => json_decode($struk->items, true),
            'total' => $struk->total_harga,
            'jumlah_item' => count(json_decode($struk->items, true)),
            'bukti_pembayaran' => $struk->foto_struk, // Use the same image path
        ]);

        return redirect()->route('pengeluarans.index')->with('success', 'Data pengeluaran berhasil dibuat dari pemasukan.');
    }

    public function show(Pengeluaran $pengeluaran)
    {
        return view('struks.pengeluaran.show', compact('pengeluaran'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $pegawais = Pegawai::all();

        // Ensure all items have subtotal calculated
        $items = is_array($pengeluaran->daftar_barang)
            ? $pengeluaran->daftar_barang
            : json_decode($pengeluaran->daftar_barang, true);

        $items = array_map(function($item) {
            if (!isset($item['subtotal'])) {
                $item['subtotal'] = $item['jumlah'] * $item['harga'];
            }
            return $item;
        }, $items);

        $pengeluaran->daftar_barang = $items;

        return view('struks.pengeluaran.edit', compact('pengeluaran', 'pegawais'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'tanggal' => 'required|date',
            // Hapus validasi untuk bukti_pembayaran karena tidak bisa diubah
        ]);

        try {
            // Hanya update pegawai_id dan tanggal
            $pengeluaran->update([
                'pegawai_id' => $validated['pegawai_id'],
                'tanggal' => $validated['tanggal'],
                // Tidak update bukti_pembayaran
            ]);

            return redirect()
                ->route('pengeluarans.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        try {
            $this->deleteFileIfExists($pengeluaran->bukti_pembayaran);
            $pengeluaran->delete();

            Log::info('Pengeluaran deleted', ['id' => $pengeluaran->id]);

            return redirect()
                ->route('pengeluarans.index')
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting pengeluaran', [
                'id' => $pengeluaran->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    private function validatePengeluaran(Request $request, ?Pengeluaran $pengeluaran = null): array
    {
        return $request->validate([
            'nama_toko' => 'required|string|max:100',
            'nomor_struk' => 'required|string|max:50|unique:pengeluarans,nomor_struk'
                . ($pengeluaran ? ',' . $pengeluaran->id : ''),
            'tanggal' => 'required|date',
            'pegawai_id' => 'required|exists:pegawais,id',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'bukti_pembayaran' => [
                $pengeluaran ? 'nullable' : 'sometimes',
                'image',
                'mimes:jpeg,png,jpg',
                'max:' . ($pengeluaran ? '2048' : '5120')
            ],
        ]);
    }

    private function processItems(array $items): array
    {
        return array_map(fn($item) => [
            'nama' => $item['nama'],
            'jumlah' => (int)$item['jumlah'],
            'harga' => (float)$item['harga'],
            'subtotal' => (float)$item['subtotal']
        ], $items);
    }

    private function calculateTotal(array $items): float
    {
        return array_reduce($items, fn($carry, $item) => $carry + $item['subtotal'], 0);
    }

    private function handleFileUpload(?UploadedFile $file = null, ?string $oldPath = null): ?string
    {
        if (!$file) return $oldPath;
        $this->deleteFileIfExists($oldPath);

        return $file->store(
            self::BUKTI_PEMBAYARAN_FOLDER,
            self::BUKTI_PEMBAYARAN_DISK
        );
    }

    private function deleteFileIfExists(?string $path): void
    {
        if ($path && Storage::disk(self::BUKTI_PEMBAYARAN_DISK)->exists($path)) {
            Storage::disk(self::BUKTI_PEMBAYARAN_DISK)->delete($path);
        }
    }

    public function exportExcel()
    {
        $pengeluarans = Pengeluaran::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Nama Toko', 'Nomor Struk', 'Tanggal', 'Pegawai', 'Total'], null, 'A1');

        $row = 2;
        foreach ($pengeluarans as $p) {
            $sheet->fromArray([
                $p->id,
                $p->nama_toko,
                $p->nomor_struk,
                $p->tanggal,
                $p->pegawai?->nama ?? '-', // pastikan eager loading `pegawai`
                $p->total
            ], null, "A$row");

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="pengeluaran.xlsx"');
        $writer->save('php://output');
        exit;
    }

    public function exportCSV()
    {
        $pengeluarans = Pengeluaran::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['ID', 'Nama Toko', 'Nomor Struk', 'Tanggal', 'Pegawai', 'Total'], null, 'A1');

        $row = 2;
        foreach ($pengeluarans as $p) {
            $sheet->fromArray([
                $p->id,
                $p->nama_toko,
                $p->nomor_struk,
                $p->tanggal,
                $p->pegawai?->nama ?? '-',
                $p->total
            ], null, "A$row");
            $row++;
        }

        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="pengeluaran.csv"');
        $writer->save('php://output');
        exit;
    }
}
