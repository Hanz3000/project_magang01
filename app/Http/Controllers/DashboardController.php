<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Count totals
        $totalStrukPemasukan = Struk::count();
        $totalStrukPengeluaran = Pengeluaran::count();
        $totalStruk = $totalStrukPemasukan + $totalStrukPengeluaran;

        // Get latest records
        $latestStruk = Struk::latest()->first();
        $latestPengeluaranStruk = Pengeluaran::latest()->first();

        // Calculate total barang masuk dan keluar
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

        // Contoh data dummy, ganti dengan query sesuai kebutuhan
        $labels = [
            now()->subDays(6)->format('d-m-Y'),
            now()->subDays(5)->format('d-m-Y'),
            now()->subDays(4)->format('d-m-Y'),
            now()->subDays(3)->format('d-m-Y'),
            now()->subDays(2)->format('d-m-Y'),
            now()->subDays(1)->format('d-m-Y'),
            now()->format('d-m-Y'),
        ];

        // Data pemasukan dan pengeluaran per hari (isi sesuai hasil query)
        $dataMasuk = [5, 8, 3, 7, 2, 4, 6];
        $dataKeluar = [2, 4, 1, 5, 3, 2, 1];

        // Return dashboard view with data
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
            'chartDataMasuk' => [
                'labels' => $labels,
                'data' => $dataMasuk,
            ],
            'chartDataKeluar' => [
                'labels' => $labels,
                'data' => $dataKeluar,
            ],
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
                    if (!isset($item['nama']) || !isset($item['jumlah'])) {
                        return null;
                    }
                    return [
                        'nama' => $item['nama'],
                        'jumlah' => (int) ($item['jumlah'] ?? 0),
                        'nomor_struk' => $struk->nomor_struk,
                        'tanggal' => $struk->tanggal_struk,
                        'tanggal_keluar' => $struk->tanggal_keluar, // Tambahkan baris ini
                    ];
                })->filter();
            })
            ->groupBy('nama')
            ->map(function ($items, $nama) {
                $latest = $items->sortByDesc('tanggal')->first();

                // Cari tanggal keluar terakhir dari tabel Pengeluaran
                $latestPengeluaran = \App\Models\Pengeluaran::query()
                    ->get()
                    ->flatMap(function ($pengeluaran) {
                        return collect($this->parseItems($pengeluaran->daftar_barang))
                            ->map(function ($item) use ($pengeluaran) {
                                return [
                                    'nama' => $item['nama'] ?? null,
                                    'tanggal' => $pengeluaran->tanggal,
                                ];
                            });
                    })
                    ->where('nama', $nama)
                    ->sortByDesc('tanggal')
                    ->first();

                return [
                    'nama' => $nama,
                    'jumlah' => $items->sum('jumlah'),
                    'nomor_struk' => $latest['nomor_struk'],
                    'tanggal' => $latest['tanggal'],
                    'tanggal_keluar' => $latest['tanggal_keluar'] ?? null, // Ambil dari struk
                ];
            })
            ->values();

        // Apply filters
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $barangList = $barangList->filter(function ($item) use ($search) {
                return stripos($item['nama'], $search) !== false ||
                    stripos($item['nomor_struk'], $search) !== false;
            });
        }

        // Apply sorting (PASTIKAN INI SETELAH GROUPBY DAN FILTER)
        $sort = $filters['sort'] ?? 'tanggal_desc';
        switch ($sort) {
            case 'nama_asc':
                $barangList = $barangList->sortBy('nama')->values();
                break;
            case 'nama_desc':
                $barangList = $barangList->sortByDesc('nama')->values();
                break;
            case 'tanggal_asc':
                $barangList = $barangList->sortBy('tanggal')->values();
                break;
            case 'tanggal_desc':
            default:
                $barangList = $barangList->sortByDesc('tanggal')->values();
                break;
        }

        return $this->paginateCollection($barangList, 10, $pageName);
    }

    protected function processAndPaginatePengeluaranList($query, array $filters = [], string $pageName = 'page')
    {
        $pengeluaranList = $query->get()
            ->flatMap(function ($pengeluaran) {
                $items = $this->parseItems($pengeluaran->daftar_barang);

                return collect($items)->map(function ($item) use ($pengeluaran) {
                    if (!isset($item['nama']) || !isset($item['jumlah'])) {
                        return null;
                    }
                    return [
                        'nama_barang' => $item['nama'],
                        'jumlah' => (int) ($item['jumlah'] ?? 0),
                        'nomor_struk' => $pengeluaran->nomor_struk,
                        'tanggal' => $pengeluaran->tanggal,
                    ];
                })->filter(); // Remove null entries
            });

        // Apply filters
        if (!empty($filters['search_pengeluaran'])) {
            $search = strtolower($filters['search_pengeluaran']);
            $pengeluaranList = $pengeluaranList->filter(function ($item) use ($search) {
                return stripos($item['nama_barang'], $search) !== false ||
                    stripos($item['nomor_struk'], $search) !== false;
            });
        }

        // Apply sorting
        $pengeluaranList = $this->sortCollection($pengeluaranList, $filters['sort_pengeluaran'] ?? null, [
            'nama_asc' => fn($c) => $c->sortBy('nama_barang'),
            'nama_desc' => fn($c) => $c->sortByDesc('nama_barang'),
            'tanggal_asc' => fn($c) => $c->sortBy('tanggal'),
            'tanggal_desc' => fn($c) => $c->sortByDesc('tanggal'),
        ], 'tanggal_desc');

        return $this->paginateCollection($pengeluaranList, 10, $pageName);
    }

    protected function generateAndPaginateHistoryBarang(string $pageName = 'page')
    {
        $masuk = Struk::query()->get()
            ->flatMap(function ($struk) {
                return collect($this->parseItems($struk->items))
                    ->map(function ($item) use ($struk) {
                        if (!isset($item['nama']) || !isset($item['jumlah'])) {
                            return null;
                        }
                        return [
                            'tipe' => 'Masuk',
                            'nama_barang' => $item['nama'],
                            'jumlah' => (int) ($item['jumlah'] ?? 0),
                            'nomor_struk' => $struk->nomor_struk,
                            'tanggal' => $struk->tanggal_struk,
                            'timestamp' => strtotime($struk->tanggal_struk),
                        ];
                    })->filter();
            });

        $keluar = Pengeluaran::query()->get()
            ->flatMap(function ($pengeluaran) {
                return collect($this->parseItems($pengeluaran->daftar_barang))
                    ->map(function ($item) use ($pengeluaran) {
                        if (!isset($item['nama']) || !isset($item['jumlah'])) {
                            return null;
                        }
                        return [
                            'tipe' => 'Keluar',
                            'nama_barang' => $item['nama'],
                            'jumlah' => (int) ($item['jumlah'] ?? 0),
                            'nomor_struk' => $pengeluaran->nomor_struk,
                            'tanggal' => $pengeluaran->tanggal,
                            'timestamp' => strtotime($pengeluaran->tanggal),
                        ];
                    })->filter();
            });

        $history = $masuk->concat($keluar)
            ->sortByDesc('timestamp')
            ->values()
            ->map(function ($item) {
                unset($item['timestamp']);
                return $item;
            });

        return $this->paginateCollection($history, 10, $pageName);
    }

    protected function parseItems($items): array
    {
        if (is_string($items)) {
            $decoded = json_decode($items, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return [];
            }
            return array_filter($decoded, function ($item) {
                return is_array($item) && isset($item['nama']) && isset($item['jumlah']);
            });
        }
        if (is_array($items)) {
            return array_filter($items, function ($item) {
                return is_array($item) && isset($item['nama']) && isset($item['jumlah']);
            });
        }
        return [];
    }

    protected function sortCollection(Collection $collection, ?string $sort, array $sortOptions, string $defaultSort): Collection
    {
        return ($sortOptions[$sort] ?? $sortOptions[$defaultSort])($collection);
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
                'path' => request()->url(),
                'pageName' => $pageName,
                'query' => request()->query(),
            ]
        );
    }
}
