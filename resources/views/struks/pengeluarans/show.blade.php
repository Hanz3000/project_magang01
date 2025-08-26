@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-10 print:py-0 print:bg-white">
    <div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-3xl overflow-hidden print:shadow-none print:rounded-none print:max-w-full print:mx-0 print:border-0">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-6 text-white print:bg-white print:text-black print:border-b print:border-gray-300">
            <h1 class="text-3xl font-bold print:text-2xl">Detail Pengeluaran</h1>
            <p class="mt-1 opacity-90 print:opacity-100 print:text-sm">Informasi lengkap struk barang</p>
        </div>

        <!-- Content -->
        <div class="p-8 space-y-10 print:p-4 print:space-y-6">

            <!-- Informasi Utama -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 print:grid-cols-2 print:gap-4">
                <div class="space-y-3 print:space-y-2">
                    <div class="print:text-sm"><span class="font-semibold text-gray-700 print:text-black">Nama Spk:</span> {{ $pengeluaran->nama_toko }}</div>
                    <div class="print:text-sm"><span class="font-semibold text-gray-700 print:text-black">Nomor Struk:</span> {{ $pengeluaran->nomor_struk }}</div>
                    <div class="print:text-sm"><span class="font-semibold text-gray-700 print:text-black">Tanggal Keluar:</span> {{ date('d M Y', strtotime($pengeluaran->tanggal)) }}</div>
                </div>
                <div class="space-y-3 print:space-y-2">
                    <div class="print:text-sm"><span class="font-semibold text-gray-700 print:text-black">Pegawai:</span> {{ $pengeluaran->pegawai?->nama ?? '-' }}</div>
                    <div class="print:text-sm"><span class="font-semibold text-gray-700 print:text-black">Jumlah Item:</span> {{ $pengeluaran->jumlah_item }} item</div>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div>
                <h2 class="text-xl font-semibold text-blue-600 print:text-black print:text-lg mb-4 print:mb-2">Daftar Barang</h2>
                <div class="overflow-x-auto print:overflow-visible">
                    <table class="min-w-full bg-white border border-slate-200 rounded-xl print:rounded-none print:border-gray-300 print:text-xs print:w-full">
                        <thead class="bg-slate-100 print:bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 border text-center print:px-2 print:py-1 print:border-gray-300">No</th>
                                <th class="px-4 py-2 border text-left print:px-2 print:py-1 print:border-gray-300">Kode Barang</th>
                                <th class="px-4 py-2 border text-left print:px-2 print:py-1 print:border-gray-300">Nama Barang</th>
                                <th class="px-4 py-2 border text-center print:px-2 print:py-1 print:border-gray-300">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengeluaran->daftar_barang as $index => $item)
                            @php
                            $kodeBarang = $item['nama'] ?? '-';
                            $barang = $masterBarang[$kodeBarang] ?? null;
                            $namaBarang = $barang?->nama_barang ?? $kodeBarang;
                            $jumlah = $item['jumlah'] ?? 0;
                            @endphp
                            <tr class="hover:bg-slate-50 print:hover:bg-transparent">
                                <td class="px-4 py-2 border text-center print:px-2 print:py-1 print:border-gray-300">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 border print:px-2 print:py-1 print:border-gray-300">{{ $kodeBarang }}</td>
                                <td class="px-4 py-2 border print:px-2 print:py-1 print:border-gray-300">{{ $namaBarang }}</td>
                                <td class="px-4 py-2 border text-center print:px-2 print:py-1 print:border-gray-300">{{ $jumlah }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
    <div>
        <strong>Status:</strong>
        <span class="ml-2 px-2 py-1 text-sm rounded 
            {{ $pengeluaran->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
            {{ ucfirst($pengeluaran->status) }}
        </span>
    </div>
</div>
            <!-- Tombol Aksi -->
            <div class="flex justify-between print:hidden">
                <a href="{{ route('pengeluarans.index') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl shadow transition">
                    Kembali
                </a>
                <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl shadow transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        @page {
            size: auto;
            margin: 10mm;
        }
        
        body {
            background: white !important;
            font-size: 12px;
            line-height: 1.4;
        }

        body * {
            visibility: hidden;
        }

        .max-w-5xl,
        .max-w-5xl * {
            visibility: visible;
        }

        .max-w-5xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none;
        }

        .print\\:hidden {
            display: none !important;
        }
        
        table {
            page-break-inside: auto;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    }
</style>
@endsection