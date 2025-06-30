<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengeluaranExport;
use App\Exports\PengeluaranCsvExport;

class PengeluaranController extends Controller
{
    // Konfigurasi
    protected const ITEMS_PER_PAGE = 10;
    protected const BUKTI_PEMBAYARAN_DISK = 'public';
    protected const BUKTI_PEMBAYARAN_FOLDER = 'bukti-pengeluaran';

    // Menampilkan daftar pengeluaran
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $pengeluarans = Pengeluaran::query()
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nama_toko', 'like', "%{$search}%")
                      ->orWhere('nomor_struk', 'like', "%{$search}%")
                      ->orWhereJsonContains('daftar_barang', ['nama' => $search]);
                });
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(self::ITEMS_PER_PAGE);

        return view('struks.pengeluaran.index', compact('pengeluarans', 'search'));
    }

    // Menampilkan form create
    public function create()
    {
        return view('struks.pengeluaran.create');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $validated = $this->validatePengeluaran($request);
        
        try {
            // Hitung total dan proses items
            $processedItems = $this->processItems($validated['items']);
            $total = $this->calculateTotal($processedItems);
            
            // Upload file jika ada
            $fotoPath = $this->handleFileUpload($request->file('bukti_pembayaran'));

            // Simpan data
            $pengeluaran = Pengeluaran::create([
                'nama_toko' => $validated['nama_toko'],
                'nomor_struk' => $validated['nomor_struk'],
                'tanggal' => $validated['tanggal'],
                'daftar_barang' => $processedItems,
                'total' => $total,
                'jumlah_item' => count($processedItems),
                'bukti_pembayaran' => $fotoPath,
            ]);

            Log::info('struks.Pengeluaran created', ['id' => $pengeluaran->id]);

            return redirect()
                ->route('struks.pengeluarans.index')
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

    // Menampilkan detail
    public function show(Pengeluaran $pengeluaran)
    {
        return view('struks.pengeluaran.show', compact('pengeluaran'));
    }

    // Menampilkan form edit
    public function edit(Pengeluaran $pengeluaran)
    {
        return view('struks.pengeluaran.edit', compact('pengeluaran'));
    }

    // Update data
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $this->validatePengeluaran($request, $pengeluaran);

        try {
            // Proses items
            $processedItems = $this->processItems($validated['items']);
            $total = $this->calculateTotal($processedItems);
            
            // Handle file upload
            $fotoPath = $this->handleFileUpload(
                $request->file('bukti_pembayaran'),
                $pengeluaran->bukti_pembayaran
            );

            // Update data
            $pengeluaran->update([
                'nama_toko' => $validated['nama_toko'],
                'nomor_struk' => $validated['nomor_struk'],
                'tanggal' => $validated['tanggal'],
                'daftar_barang' => $processedItems,
                'total' => $total,
                'jumlah_item' => count($processedItems),
                'bukti_pembayaran' => $fotoPath ?? $pengeluaran->bukti_pembayaran,
            ]);

            Log::info('struks.Pengeluaran updated', ['id' => $pengeluaran->id]);

            return redirect()
                ->route('struks.pengeluarans.index')
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

    // Hapus data
    public function destroy(Pengeluaran $pengeluaran)
    {
        try {
            // Hapus file jika ada
            $this->deleteFileIfExists($pengeluaran->bukti_pembayaran);
            
            $pengeluaran->delete();

            Log::info('struks.Pengeluaran deleted', ['id' => $pengeluaran->id]);

            return redirect()
                ->route('struks.pengeluarans.index')
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

    // ============ METHOD BANTUAN PRIVATE ============

    private function validatePengeluaran(Request $request, ?Pengeluaran $pengeluaran = null): array
    {
        return $request->validate([
            'nama_toko' => 'required|string|max:100',
            'nomor_struk' => 'required|string|max:50|unique:pengeluarans,nomor_struk' 
                . ($pengeluaran ? ','.$pengeluaran->id : ''),
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'bukti_pembayaran' => [
                $pengeluaran ? 'nullable' : 'sometimes',
                'image',
                'mimes:jpeg,png,jpg',
                'max:'.($pengeluaran ? '2048' : '5120')
            ],
        ]);
    }

    private function processItems(array $items): array
    {
        return array_map(function ($item) {
            return [
                'nama' => $item['nama'],
                'jumlah' => (int)$item['jumlah'],
                'harga' => (float)$item['harga'],
                'subtotal' => (float)$item['subtotal']
            ];
        }, $items);
    }

    private function calculateTotal(array $items): float
    {
        return array_reduce($items, function ($carry, $item) {
            return $carry + $item['subtotal'];
        }, 0);
    }

    private function handleFileUpload(?UploadedFile $file = null, ?string $oldPath = null): ?string
    {
        if (!$file) {
            return $oldPath;
        }

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

    // Ekspor data
    public function exportExcel()
    {
        return Excel::download(new PengeluaranExport, 'pengeluaran-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportCSV()
    {
        return Excel::download(new PengeluaranCsvExport, 'pengeluaran-'.now()->format('Y-m-d').'.csv');
    }

    // Download/view bukti pembayaran
    public function downloadBukti(Pengeluaran $pengeluaran)
    {
        $this->validateBuktiPembayaran($pengeluaran->bukti_pembayaran);
        
        $path = Storage::disk(self::BUKTI_PEMBAYARAN_DISK)
            ->path($pengeluaran->bukti_pembayaran);

        return response()->download($path);
    }

    public function viewBukti(Pengeluaran $pengeluaran)
    {
        $this->validateBuktiPembayaran($pengeluaran->bukti_pembayaran);
        
        $path = Storage::disk(self::BUKTI_PEMBAYARAN_DISK)
            ->path($pengeluaran->bukti_pembayaran);

        return response()->file($path);
    }

    private function validateBuktiPembayaran(?string $path): void
    {
        if (!$path) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        if (!Storage::disk(self::BUKTI_PEMBAYARAN_DISK)->exists($path)) {
            abort(404, 'File bukti pembayaran tidak ditemukan');
        }
    }
}