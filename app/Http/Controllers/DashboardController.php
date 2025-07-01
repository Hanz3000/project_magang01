<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Count records
        $totalStrukPemasukan = Struk::count();
        $totalStrukPengeluaran = Pengeluaran::count();
        $totalStruk = $totalStrukPemasukan + $totalStrukPengeluaran;

        // Get latest records
        $latestStruk = Struk::latest()->first();
        $latestPengeluaranStruk = Pengeluaran::latest()->first();

        // Total jumlah barang masuk
        $totalBarangMasuk = Struk::all()->reduce(function ($carry, $struk) {
            $items = is_string($struk->items) ? json_decode($struk->items, true) : $struk->items;

            foreach ($items as $item) {
                $carry += isset($item['jumlah']) ? (int)$item['jumlah'] : 0;
            }
            return $carry;
        }, 0);

        // Total jumlah barang keluar
        $totalBarangKeluar = Pengeluaran::all()->reduce(function ($carry, $pengeluaran) {
            $items = is_string($pengeluaran->daftar_barang) ? json_decode($pengeluaran->daftar_barang, true) : $pengeluaran->daftar_barang;

            foreach ($items as $item) {
                $carry += isset($item['jumlah']) ? (int)$item['jumlah'] : 0;
            }
            return $carry;
        }, 0);

        // Proses list barang masuk
        $barangList = $this->processBarangList(
            Struk::all(),
            $request->input('search'),
            $request->input('sort')
        );

        // Proses list barang keluar
        $pengeluaranBarangList = collect();

        foreach (Pengeluaran::all() as $pengeluaran) {
            $items = is_string($pengeluaran->daftar_barang) ? json_decode($pengeluaran->daftar_barang, true) : $pengeluaran->daftar_barang;

            foreach ($items as $item) {
                $pengeluaranBarangList->push([
                    'nama_barang' => $item['nama'],
                    'jumlah' => $item['jumlah'],
                    'nomor_struk' => $pengeluaran->nomor_struk,
                    'tanggal' => $pengeluaran->tanggal,
                ]);
            }
        }

        // Filter
        if ($request->filled('search_pengeluaran')) {
            $search = strtolower($request->search_pengeluaran);
            $pengeluaranBarangList = $pengeluaranBarangList->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['nama_barang']), $search) ||
                    str_contains(strtolower($item['nomor_struk']), $search);
            });
        }

        // Sort
        switch ($request->sort_pengeluaran) {
            case 'nama_asc':
                $pengeluaranBarangList = $pengeluaranBarangList->sortBy(function ($item) {
                    return strtolower($item['nama_barang']);
                });
                break;
            case 'nama_desc':
                $pengeluaranBarangList = $pengeluaranBarangList->sortByDesc(function ($item) {
                    return strtolower($item['nama_barang']);
                });
                break;

            case 'tanggal_asc':
                $pengeluaranBarangList = $pengeluaranBarangList->sortBy('tanggal');
                break;
            case 'tanggal_desc':
            default:
                $pengeluaranBarangList = $pengeluaranBarangList->sortByDesc('tanggal');
                break;
        }

        // Paginate
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // default pakai 'page'

        $currentItems = $pengeluaranBarangList->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $pengeluaranBarangList = new LengthAwarePaginator(
            $currentItems,
            $pengeluaranBarangList->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('dashboard', [
            'barangList' => $barangList,
            'pengeluaranBarangList' => $pengeluaranBarangList,
            'totalStruk' => $totalStruk,
            'latestStruk' => $latestStruk,
            'latestPengeluaranStruk' => $latestPengeluaranStruk,
            'totalPemasukan' => $totalStrukPemasukan,
            'totalPengeluaran' => $totalStrukPengeluaran,
            'totalBarangMasuk' => $totalBarangMasuk,
            'totalBarangKeluar' => $totalBarangKeluar,
        ]);
    }

    protected function processBarangList($struks, $search = null, $sort = null)
    {
        $barangList = [];

        foreach ($struks as $struk) {
            $items = json_decode($struk->items, true) ?? [];

            foreach ($items as $item) {
                if (isset($item['nama'], $item['jumlah'])) {
                    $nama = $item['nama'];

                    if (!isset($barangList[$nama])) {
                        $barangList[$nama] = [
                            'jumlah' => $item['jumlah'],
                            'nomor_struk' => $struk->nomor_struk,
                            'tanggal' => $struk->tanggal_struk
                        ];
                    } else {
                        $barangList[$nama]['jumlah'] += $item['jumlah'];
                        if ($struk->tanggal_struk > $barangList[$nama]['tanggal']) {
                            $barangList[$nama]['nomor_struk'] = $struk->nomor_struk;
                            $barangList[$nama]['tanggal'] = $struk->tanggal_struk;
                        }
                    }
                }
            }
        }

        if ($search) {
            $barangList = collect($barangList)
                ->filter(function ($item, $nama) use ($search) {
                    return stripos($nama, $search) !== false ||
                        stripos($item['nomor_struk'], $search) !== false;
                })
                ->toArray();
        }

        $barangList = collect($barangList);

        switch ($sort) {
            case 'nama_asc':
                $barangList = $barangList->sortKeysUsing(function ($a, $b) {
                    return strcasecmp($a, $b); // case-insensitive comparison
                });
                break;
            case 'nama_desc':
                $barangList = $barangList->sortKeysUsing(function ($a, $b) {
                    return strcasecmp($b, $a); // reverse case-insensitive comparison
                });
                break;

            case 'nama_desc':
                $barangList = $barangList->sortKeysDesc();
                break;
            case 'tanggal_desc':
            default:
                $barangList = $barangList->sortByDesc('tanggal');
                break;
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $barangList->slice(($currentPage - 1) * $perPage, $perPage)->all();

        return new LengthAwarePaginator(
            $currentItems,
            $barangList->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
