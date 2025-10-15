@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-10 print:py-0 print:bg-white">
    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-3xl overflow-hidden print:shadow-none print:rounded-none print:max-w-full print:mx-0 print:border-0">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-6 text-white text-center print:bg-white print:text-black print:border-b print:border-gray-300">
            <h1 class="text-3xl font-bold print:text-2xl">Detail Struk</h1>
            <p class="text-white/90 print:text-gray-700 print:text-sm">Informasi lengkap struk barang</p>
        </div>

        <!-- Isi -->
        <div class="p-8 space-y-10 print:p-4 print:space-y-6">

            <!-- Informasi Utama -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 border border-slate-100 rounded-2xl p-6 print:bg-white print:border-gray-300 print:p-3">
                <div class="space-y-3 print:space-y-2">
                    <div>
                        <span class="text-gray-500 print:text-black text-sm">Nama Toko</span>
                        <p class="text-xl font-bold text-gray-900 print:text-black">{{ $pengeluaran->nama_toko }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500 print:text-black text-sm">Tanggal Masuk</span>
                        <p class="text-lg font-semibold text-gray-900 print:text-black">
                            {{ date('d M Y', strtotime($pengeluaran->tanggal)) }}
                        </p>
                    </div>
                </div>
                <div class="space-y-3 print:space-y-2">
                    <div>
                        <span class="text-gray-500 print:text-black text-sm">Nomor Struk</span>
                        <p class="text-xl font-bold text-gray-900 print:text-black">{{ $pengeluaran->nomor_struk }}</p>
                    </div>
                    {{-- Total Pembayaran dihapus sesuai permintaan --}}
                </div>
            </div>

            <!-- Daftar Barang -->
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 print:text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-800 print:text-black">Daftar Barang</h2>
                </div>

                <!-- Mode Desktop -->
                <div class="hidden md:block overflow-x-auto print:overflow-visible">
                    <table class="min-w-full border border-slate-200 rounded-xl print:rounded-none print:border-gray-300 print:text-xs print:w-full">
                        <thead class="bg-slate-100 print:bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 border text-center">NO</th>
                                <th class="px-4 py-2 border text-left">KODE</th>
                                <th class="px-4 py-2 border text-left">NAMA</th>
                                <th class="px-4 py-2 border text-center">JUMLAH</th>
                                {{-- <th class="px-4 py-2 border text-center">HARGA</th>
                                <th class="px-4 py-2 border text-center">SUBTOTAL</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengeluaran->daftar_barang as $index => $item)
                            @php
                                $kodeBarang = $item['nama'] ?? '-';
                                $barang = $masterBarang[$kodeBarang] ?? null;
                                $namaBarang = $barang?->nama_barang ?? $kodeBarang;
                                $jumlah = $item['jumlah'] ?? 0;
                                // $harga = $item['harga'] ?? 0;
                                // $subtotal = $jumlah * $harga;
                            @endphp
                            <tr class="hover:bg-slate-50 print:hover:bg-transparent">
                                <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 border">{{ $kodeBarang }}</td>
                                <td class="px-4 py-2 border font-semibold text-gray-800">{{ $namaBarang }}</td>
                                <td class="px-4 py-2 border text-center">{{ $jumlah }}</td>
                                {{-- <td class="px-4 py-2 border text-center text-indigo-600 print:text-black">Rp{{ number_format($harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 border text-center text-indigo-600 print:text-black">Rp{{ number_format($subtotal, 0, ',', '.') }}</td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mode Mobile -->
                <div class="md:hidden space-y-4">
                    @foreach ($pengeluaran->daftar_barang as $index => $item)
                    @php
                        $kodeBarang = $item['nama'] ?? '-';
                        $barang = $masterBarang[$kodeBarang] ?? null;
                        $namaBarang = $barang?->nama_barang ?? $kodeBarang;
                        $jumlah = $item['jumlah'] ?? 0;
                        // $harga = $item['harga'] ?? 0;
                        // $subtotal = $jumlah * $harga;
                    @endphp
                    <div class="border border-slate-200 rounded-xl p-4 shadow-sm bg-white">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">No</span>
                            <span>{{ $index + 1 }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Kode</span>
                            <span>{{ $kodeBarang }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Nama</span>
                            <span class="font-semibold">{{ $namaBarang }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Jumlah</span>
                            <span>{{ $jumlah }}</span>
                        </div>
                        {{-- 
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Harga</span>
                            <span class="text-indigo-600">Rp{{ number_format($harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="text-indigo-600 font-semibold">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        --}}
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="flex justify-start print:hidden">
                <a href="{{ route('pengeluarans.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl shadow transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    @page { size: auto; margin: 10mm; }
    body { background: white !important; font-size: 12px; line-height: 1.4; }
    body * { visibility: hidden; }
    .max-w-4xl, .max-w-4xl * { visibility: visible; }
    .max-w-4xl { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; box-shadow: none; }
    .print\:hidden { display: none !important; }
}
</style>
@endsection
