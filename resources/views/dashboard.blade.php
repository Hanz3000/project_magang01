@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="max-w-7xl mx-auto px-6 py-10">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-slate-800 mb-2">Dashboard</h1>
            <p class="text-slate-500">Selamat datang kembali, <span class="font-semibold">{{ Auth::user()->name }}</span></p>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <!-- Total Struk -->
            <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition p-6">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mr-5">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Total Struk</p>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalStruk }}</p>
                    </div>
                </div>
            </div>

            <!-- Tanggal -->
            <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition p-6">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mr-5">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Hari Ini</p>
                        <p class="text-3xl font-bold text-slate-800">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Struk Terbaru -->
        <div class="bg-white rounded-3xl shadow-md p-6 mb-10">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">Struk Terbaru</h2>

            @if ($latestStruk)
            <div class="bg-gradient-to-r from-orange-50 to-white p-6 rounded-2xl border border-orange-200">
                <div class="space-y-2">
                    <p><span class="font-medium text-slate-600">Toko:</span> <span class="font-semibold">{{ $latestStruk->nama_toko }}</span></p>
                    <p><span class="font-medium text-slate-600">Nomor Struk:</span> <span class="font-semibold">{{ $latestStruk->nomor_struk }}</span></p>
                    <p><span class="font-medium text-slate-600">Tanggal:</span> <span class="font-semibold">{{ $latestStruk->tanggal_struk }}</span></p>
                    <p><span class="font-medium text-slate-600">Total:</span> <span class="font-semibold text-emerald-600">Rp{{ number_format($latestStruk->total_harga, 0, ',', '.') }}</span></p>
                </div>
            </div>
            @else
            <p class="text-slate-500">Belum ada struk.</p>
            @endif
        </div>

        <!-- Tabel Barang -->
        <div class="bg-white rounded-3xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-4">
                <h2 class="text-2xl font-bold text-slate-800">Daftar Barang</h2>
                <form action="{{ route('dashboard') }}" method="GET" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="border border-slate-300 rounded-l-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Cari barang...">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-r-xl hover:bg-blue-700 transition">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="bg-gradient-to-r from-blue-100 to-blue-50 text-slate-700">
                        <tr>
                            <th class="px-6 py-4 border-b">No</th>
                            <th class="px-6 py-4 border-b">Nama Barang</th>
                            <th class="px-6 py-4 border-b">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangList as $nama => $jumlah)
                        <tr class="hover:bg-blue-50 border-b">
                            <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $nama }}</td>
                            <td class="px-6 py-4 text-center">{{ $jumlah }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-slate-500">Data tidak ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection