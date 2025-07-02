<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    const ITEMS_PER_PAGE = 10;
    
    public function index(Request $request)
    {
        // Count totals
        $totalStrukPemasukan = Struk::count();
        $totalStrukPengeluaran = Pengeluaran::count();
        $totalStruk = $totalStrukPemasukan + $totalStrukPengeluaran;

        // Get latest records
        $latestStruk = Struk::latest()->first();
        $latestPengeluaranStruk = Pengeluaran::latest()->first();

        // Calculate totals
        $totalBarangMasuk = $this->calculateTotalBarang(Struk::class, 'items');
        $totalBarangKeluar = $this->calculateTotalBarang(Pengeluaran::class, 'daftar_barang');

        // Process lists with proper pagination
        $barangList = $this->processAndPaginateBarangList(
            Struk::query(),
            $request->only(['search', 'sort']),
            'page_barang'
        );

        $pengeluaranBarangList = $this->processAndPaginatePengeluaranList(
            Pengeluaran::query(),
            $request->only(['search_pengeluaran', 'sort_pengeluaran']),
            'page_pengeluaran'
        );

        // Generate and paginate history
        $historyBarang = $this->generateAndPaginateHistoryBarang('page_history');

        return view('dashboard', [
            'barangList' => $barangList,
            'pengeluaranBarangList' => $pengeluaranBarangList,
            'historyBarang' => $historyBarang,
            'totalStruk' => $totalStruk,
            'latestStruk' => $latestStruk,
            'latestPengeluaranStruk' => $latestPengeluaranStruk,
            'totalPemasukan' => $totalStrukPemasukan,
            'totalPengeluaran' => $totalStrukPengeluaran,
            'totalBarangMasuk' => $totalBarangMasuk,
            'totalBarangKeluar' => $totalBarangKeluar,
        ]);
    }

    protected function calculateTotalBarang(string $model, string $itemsField): int
    {
        return $model::query()
            ->get()
            ->reduce(function ($carry, $record) use ($itemsField) {
                $items = $this->parseItems($record->{$itemsField});
                return $carry + collect($items)->sum('jumlah');
            }, 0);
    }

    protected function processAndPaginateBarangList($query, array $filters = [], string $pageName = 'page')
    {
        $barangList = $query->get()
            ->flatMap(function ($struk) {
                $items = $this->parseItems($struk->items);
                
                return collect($items)->map(function ($item) use ($struk) {
                    return [
                        'nama' => $item['nama'] ?? 'Unknown',
                        'jumlah' => (int) ($item['jumlah'] ?? 0),
                        'nomor_struk' => $struk->nomor_struk,
                        'tanggal' => $struk->tanggal_struk,
                    ];
                });
            })
            ->groupBy('nama')
            ->map(function ($items, $nama) {
                $latest = $items->sortByDesc('tanggal')->first();
                return [
                    'nama' => $nama,
                    'jumlah' => $items->sum('jumlah'),
                    'nomor_struk' => $latest['nomor_struk'] ?? null,
                    'tanggal' => $latest['tanggal'] ?? null,
                ];
            })
            ->values();

        // Apply filters
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $barangList = $barangList->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['nama']), $search) || 
                       str_contains(strtolower($item['nomor_struk'] ?? ''), $search);
            });
        }

        // Apply sorting
        $barangList = $this->sortCollection($barangList, $filters['sort'] ?? null, [
            'nama_asc' => fn($c) => $c->sortBy('nama'),
            'nama_desc' => fn($c) => $c->sortByDesc('nama'),
            'tanggal_asc' => fn($c) => $c->sortBy('tanggal'),
            'tanggal_desc' => fn($c) => $c->sortByDesc('tanggal'),
        ], 'tanggal_desc');

        return $this->paginateCollection($barangList, self::ITEMS_PER_PAGE, $pageName);
    }

    protected function processAndPaginatePengeluaranList($query, array $filters = [], string $pageName = 'page')
    {
        $pengeluaranList = $query->get()
            ->flatMap(function ($pengeluaran) {
                $items = $this->parseItems($pengeluaran->daftar_barang);
                
                return collect($items)->map(function ($item) use ($pengeluaran) {
                    return [
                        'nama_barang' => $item['nama'] ?? 'Unknown',
                        'jumlah' => (int) ($item['jumlah'] ?? 0),
                        'nomor_struk' => $pengeluaran->nomor_struk,
                        'tanggal' => $pengeluaran->tanggal,
                    ];
                });
            });

        // Apply filters
        if (!empty($filters['search_pengeluaran'])) {
            $search = strtolower($filters['search_pengeluaran']);
            $pengeluaranList = $pengeluaranList->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['nama_barang']), $search) ||
                       str_contains(strtolower($item['nomor_struk']), $search);
            });
        }

        // Apply sorting
        $pengeluaranList = $this->sortCollection($pengeluaranList, $filters['sort_pengeluaran'] ?? null, [
            'nama_asc' => fn($c) => $c->sortBy('nama_barang'),
            'nama_desc' => fn($c) => $c->sortByDesc('nama_barang'),
            'tanggal_asc' => fn($c) => $c->sortBy('tanggal'),
            'tanggal_desc' => fn($c) => $c->sortByDesc('tanggal'),
        ], 'tanggal_desc');

        return $this->paginateCollection($pengeluaranList, self::ITEMS_PER_PAGE, $pageName);
    }

    protected function generateAndPaginateHistoryBarang(string $pageName = 'page')
    {
        $masuk = Struk::query()->get()
            ->flatMap(function ($struk) {
                $items = $this->parseItems($struk->items);
                return collect($items)->map(function ($item) use ($struk) {
                    return [
                        'tipe' => 'Masuk',
                        'nama_barang' => $item['nama'] ?? 'Unknown',
                        'jumlah' => (int) ($item['jumlah'] ?? 0),
                        'nomor_struk' => $struk->nomor_struk,
                        'tanggal_masuk' => $struk->tanggal_struk ?? null,
                        'tanggal_keluar' => null,
                        'timestamp' => strtotime($struk->tanggal_struk),
                    ];
                });
            });

        $keluar = Pengeluaran::query()->get()
            ->flatMap(function ($pengeluaran) {
                $items = $this->parseItems($pengeluaran->daftar_barang);
                return collect($items)->map(function ($item) use ($pengeluaran) {
                    return [
                        'tipe' => 'Keluar',
                        'nama_barang' => $item['nama'] ?? 'Unknown',
                        'jumlah' => (int) ($item['jumlah'] ?? 0),
                        'nomor_struk' => $pengeluaran->nomor_struk,
                        'tanggal_masuk' => null,
                        'tanggal_keluar' => $pengeluaran->tanggal ?? null,
                        'timestamp' => strtotime($pengeluaran->tanggal),
                    ];
                });
            });

        $history = $masuk->concat($keluar)
            ->sortByDesc('timestamp')
            ->values()
            ->map(function ($item) {
                unset($item['timestamp']);
                return $item;
            });

        return $this->paginateCollection($history, self::ITEMS_PER_PAGE, $pageName);
    }

    protected function parseItems($items): array
    {
        if (is_array($items)) {
            return $items;
        }

        if (is_string($items) && !empty($items)) {
            $decoded = json_decode($items, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    protected function sortCollection(Collection $collection, ?string $sort, array $sortOptions, string $defaultSort): Collection
    {
        $sortCallback = $sortOptions[$sort] ?? $sortOptions[$defaultSort];
        return $sortCallback($collection);
    }

    protected function paginateCollection(Collection $collection, int $perPage = 10, string $pageName = 'page'): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => $pageName,
                'query' => request()->query(),
            ]
        );
    }
}