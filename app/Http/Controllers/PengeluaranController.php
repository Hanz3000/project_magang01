<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengeluaranExport;
use App\Exports\PengeluaranCsvExport;

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
            ->orderBy('tanggal', 'desc')
            ->paginate(self::ITEMS_PER_PAGE);

        return view('struks.pengeluaran.index', compact('pengeluarans', 'search'));
    }

    public function create()
    {
        $pegawais = Pegawai::all();
        return view('struks.pengeluaran.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePengeluaran($request);

        try {
            $processedItems = $this->processItems($validated['items']);
            $total = $this->calculateTotal($processedItems);
            $fotoPath = $this->handleFileUpload($request->file('bukti_pembayaran'));

            $pengeluaran = Pengeluaran::create([
                'nama_toko' => $validated['nama_toko'],
                'nomor_struk' => $validated['nomor_struk'],
                'tanggal' => $validated['tanggal'],
                'pegawai_id' => $validated['pegawai_id'],
                'daftar_barang' => $processedItems,
                'total' => $total,
                'jumlah_item' => count($processedItems),
                'bukti_pembayaran' => $fotoPath,
            ]);

            Log::info('Pengeluaran created', ['id' => $pengeluaran->id]);

            return redirect()
                ->route('pengeluarans.index')
                ->with('success', 'Data pengeluaran berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Error saving pengeluaran', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Pengeluaran $pengeluaran)
    {
        return view('struks.pengeluaran.show', compact('pengeluaran'));
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        $pegawais = Pegawai::all();
        return view('struks.pengeluaran.edit', compact('pengeluaran', 'pegawais'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $this->validatePengeluaran($request, $pengeluaran);

        try {
            $processedItems = $this->processItems($validated['items']);
            $total = $this->calculateTotal($processedItems);
            $fotoPath = $this->handleFileUpload($request->file('bukti_pembayaran'), $pengeluaran->bukti_pembayaran);

            $pengeluaran->update([
                'nama_toko' => $validated['nama_toko'],
                'nomor_struk' => $validated['nomor_struk'],
                'tanggal' => $validated['tanggal'],
                'pegawai_id' => $validated['pegawai_id'],
                'daftar_barang' => $processedItems,
                'total' => $total,
                'jumlah_item' => count($processedItems),
                'bukti_pembayaran' => $fotoPath ?? $pengeluaran->bukti_pembayaran,
            ]);

            Log::info('Pengeluaran updated', ['id' => $pengeluaran->id]);

            return redirect()
                ->route('pengeluarans.index')
                ->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating pengeluaran', [
                'id' => $pengeluaran->id,
                'error' => $e->getMessage()
            ]);

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
        return Excel::download(new PengeluaranExport, 'pengeluaran-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportCSV()
    {
        return Excel::download(new PengeluaranCsvExport, 'pengeluaran-' . now()->format('Y-m-d') . '.csv');
    }
}
