<?php

namespace App\Http\Controllers;

use App\Models\Struk;
use App\Models\Pengeluaran;
use App\Models\Barang;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Menampilkan form transaksi gabungan
     */
    public function create()
    {
        $barangList = Barang::all();
        $pegawais = Pegawai::all();
        
        return view('transaksi.create', [
            'barangList' => $barangList,
            'pegawais' => $pegawais
        ]);
    }

    /**
     * Menyimpan data struk (pemasukan)
     */
    public function storeStruk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_toko' => 'required|string|max:255',
            'nomor_struk' => 'required|string|max:255|unique:struks,nomor_struk',
            'tanggal_struk' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'foto_struk' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fotoPath = null;
        if ($request->hasFile('foto_struk')) {
            $fotoPath = $request->file('foto_struk')->store('struk_foto', 'public');
        }

        $struk = Struk::create([
            'nama_toko' => $request->nama_toko,
            'nomor_struk' => $request->nomor_struk,
            'tanggal_struk' => $request->tanggal_struk,
            'items' => json_encode($request->items),
            'total_harga' => $request->total_harga,
            'foto_struk' => $fotoPath ? basename($fotoPath) : null
        ]);

        return redirect()->route('struks.index')
            ->with('success', 'Struk berhasil disimpan!');
    }

    /**
     * Menyimpan data pengeluaran
     */
    public function storePengeluaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_toko' => 'required|string|max:255',
            'nomor_struk' => 'required|string|max:255|unique:pengeluarans,nomor_struk',
            'tanggal' => 'required|date',
            'pegawai_id' => 'required|exists:pegawais,id',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pengeluaran', 'public');
        }

        $pengeluaran = Pengeluaran::create([
            'nama_toko' => $request->nama_toko,
            'nomor_struk' => $request->nomor_struk,
            'tanggal' => $request->tanggal,
            'pegawai_id' => $request->pegawai_id,
            'daftar_barang' => json_encode($request->items),
            'total' => $request->total,
            'jumlah_item' => count($request->items),
            'bukti_pembayaran' => $buktiPath ? basename($buktiPath) : null
        ]);

        return redirect()->route('pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil disimpan!');
    }

    /**
     * Autocomplete untuk pencarian barang
     */
    public function autocompleteItems(Request $request)
    {
        $term = $request->get('term');
        
        $results = Barang::where('nama_barang', 'LIKE', '%'.$term.'%')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'value' => $item->nama_barang,
                    'label' => $item->nama_barang,
                    'harga' => $item->harga
                ];
            });

        return response()->json($results);
    }

    /**
     * Pencarian barang untuk struk
     */
    public function searchBarang(Request $request)
    {
        $search = $request->get('q');
        
        $barangs = Barang::where('nama_barang', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get();

        return response()->json($barangs);
    }
}