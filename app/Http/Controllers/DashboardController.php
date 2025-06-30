<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalStruk = Struk::count();
        $latestStruk = Struk::latest()->first();

        $barangList = [];

        $struks = Struk::all();

        foreach ($struks as $struk) {
            $items = json_decode($struk->items, true);

            if (is_array($items)) {
                foreach ($items as $item) {
                    if (isset($item['nama']) && isset($item['jumlah'])) {
                        $nama = $item['nama'];
                        $jumlah = $item['jumlah'];

                        if (!isset($barangList[$nama])) {
                            $barangList[$nama] = [
                                'jumlah' => $jumlah,
                                'nomor_struk' => $struk->nomor_struk,
                                'tanggal' => $struk->tanggal_struk // Add this line
                            ];
                        } else {
                            $barangList[$nama]['jumlah'] += $jumlah;
                            $barangList[$nama]['nomor_struk'] = $struk->nomor_struk;
                            $barangList[$nama]['tanggal'] = $struk->tanggal_struk; // Add this line
                        }
                    }
                }
            }
        }

        // Filter pencarian
        $search = $request->input('search');
        if ($search) {
            $barangList = collect($barangList)
                ->filter(function ($jumlah, $nama) use ($search) {
                    return stripos($nama, $search) !== false;
                })
                ->toArray();
        }

        $latestPengeluaranItems = \App\Models\Pengeluaran::latest()->take(5)->get();

        return view('dashboard', compact('barangList', 'totalStruk', 'latestStruk', 'latestPengeluaranItems'));
    }
}
