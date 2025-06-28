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

                        // Simpan jumlah dan nomor_struk
                        if (!isset($barangList[$nama])) {
                            $barangList[$nama] = [
                                'jumlah' => $jumlah,
                                'nomor_struk' => $struk->nomor_struk,
                            ];
                        } else {
                            $barangList[$nama]['jumlah'] += $jumlah;
                            // Jika ingin menampilkan nomor_struk terakhir, update di sini
                            $barangList[$nama]['nomor_struk'] = $struk->nomor_struk;
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

        return view('dashboard', compact('totalStruk', 'latestStruk', 'barangList'));
    }
}
