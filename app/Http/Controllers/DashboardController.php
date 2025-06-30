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
        $barangList = $this->processBarangList(Struk::all(), $request->input('search'));

        // Barang keluar untuk tabel pengeluaran
        $latestPengeluaranItems = Pengeluaran::query()
            ->when($request->has('search'), function ($query) use ($request) {
                $search = strtolower($request->search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(nama_toko) like ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(nomor_struk) like ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(daftar_barang::text) like ?', ["%{$search}%"]);
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'pengeluaran_page');

        return view('dashboard', [
            'barangList' => $barangList,
            'totalStruk' => $totalStruk,
            'latestStruk' => $latestStruk,
            'latestPengeluaranItems' => $latestPengeluaranItems,
            'latestPengeluaranStruk' => $latestPengeluaranStruk,
            'totalPemasukan' => $totalStrukPemasukan,
            'totalPengeluaran' => $totalStrukPengeluaran,
            'totalBarangMasuk' => $totalBarangMasuk,
            'totalBarangKeluar' => $totalBarangKeluar,
        ]);
    }


    protected function processBarangList($struks, $search = null)
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
                        // Update only if newer struk
                        if ($struk->tanggal_struk > $barangList[$nama]['tanggal']) {
                            $barangList[$nama]['nomor_struk'] = $struk->nomor_struk;
                            $barangList[$nama]['tanggal'] = $struk->tanggal_struk;
                        }
                    }
                }
            }
        }

        // Apply search filter if provided
        if ($search) {
            $barangList = collect($barangList)
                ->filter(function ($item, $nama) use ($search) {
                    return stripos($nama, $search) !== false ||
                        stripos($item['nomor_struk'], $search) !== false;
                })
                ->toArray();
        }

        // Paginate the results
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = collect($barangList)->slice(($currentPage - 1) * $perPage, $perPage)->all();

        return new LengthAwarePaginator(
            $currentItems,
            count($barangList),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
