@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Analisis</h1>
            <p class="mt-2 text-gray-600">Selamat datang kembali, <span
                    class="font-medium text-blue-600">{{ Auth::user()->name ?? 'Pengguna' }}</span>!</p>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div
                class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                        <<<<<<< HEAD
                            <!-- Ikon Struk -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                =======
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    >>>>>>> 037a7172e078f734a3a849182961ee2343a2e024
                                </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Struk</p>
                        <p class="text-2xl font-semibold text-gray-800 whitespace-nowrap">{{ $totalStruk ?? '0' }}</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
                        <<<<<<< HEAD
                            <!-- Ikon Barang Masuk -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                =======
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    >>>>>>> 037a7172e078f734a3a849182961ee2343a2e024
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 whitespace-nowrap">Total Barang Masuk</p>
                        <p class="text-2xl font-semibold text-gray-800 whitespace-nowrap">{{ $totalBarangMasuk ?? '0' }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-50 text-red-600 mr-4">
                        <<<<<<< HEAD
                            <!-- Ikon Barang Keluar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                =======
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    >>>>>>> 037a7172e078f734a3a849182961ee2343a2e024
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 8V4" />
                                </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 whitespace-nowrap">Total Barang Keluar</p>
                        <p class="text-2xl font-semibold text-gray-800 whitespace-nowrap">
                            {{ $totalBarangKeluar ?? '0' }}
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 hover:shadow transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
                        <<<<<<< HEAD
                            <!-- Ikon Tanggal -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                =======
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    >>>>>>> 037a7172e078f734a3a849182961ee2343a2e024
                                </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Hari Ini</p>
                        <p class="text-lg font-semibold text-gray-800 whitespace-nowrap">{{ now()->format('d-m-Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Struk Terbaru & Struk Pengeluaran Terbaru (Side by Side) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Struk Terbaru -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-2 5a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        Struk Pemasukan Terbaru
                    </h2>
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Update Terkini</span>
                </div>

                @if (isset($latestStruk) && $latestStruk)
                <div class="space-y-3 text-gray-700">
                    <div class="flex items-center">
                        <span class="w-24 text-gray-500">Nama Toko:</span>
                        <span class="font-medium">{{ $latestStruk->nama_toko }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-24 text-gray-500">Nomor Struk:</span>
                        <span class="font-mono">{{ $latestStruk->nomor_struk }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-24 text-gray-500">Tanggal:</span>
                        <span>{{ $latestStruk->tanggal_struk }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-24 text-gray-500">Nama Barang:</span>
                        <span class="font-medium">
                            @php
                            $items = is_string($latestStruk->items ?? null) ? json_decode($latestStruk->items, true) :
                            ($latestStruk->items ?? []);
                            @endphp
                            @if(!empty($items))
                            {{ implode(', ', array_column($items, 'nama')) }}
                            @else
                            -
                            @endif
                        </span>
                    </div>

                </div>
                @else
                <div class="text-center py-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2 text-gray-600">Belum ada struk terbaru tercatat.</p>
                </div>
                @endif
            </div>

            <!-- Struk Pengeluaran Terbaru -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V7h2v2z"
                                clip-rule="evenodd" />
                        </svg>
                        Struk Pengeluaran Terbaru
                    </h2>
                    <span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full">Update Terkini</span>
                </div>

                @if (isset($latestPengeluaranStruk) && $latestPengeluaranStruk)
                <div class="space-y-3 text-gray-700">
                    <div class="flex items-center">
                        <span class="w-24 text-gray-500">Nama Toko:</span>
                        <span class="font-medium">{{ $latestPengeluaranStruk->nama_toko ?? '-' }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-24 text-gray-500">Nomor Struk:</span>
                        <span class="font-mono">{{ $latestPengeluaranStruk->nomor_struk }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-24 text-gray-500">Tanggal:</span>
                        <span>{{ $latestPengeluaranStruk->tanggal ?? '-' }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-24 text-gray-500">Barang Keluar:</span>
                        <span class="font-medium">
                            @php
                            $itemsKeluar = is_string($latestPengeluaranStruk->daftar_barang ?? null)
                            ? json_decode($latestPengeluaranStruk->daftar_barang, true)
                            : ($latestPengeluaranStruk->daftar_barang ?? []);
                            @endphp

                            @if(!empty($itemsKeluar))
                            {{ implode(', ', array_column($itemsKeluar, 'nama')) }}
                            @else
                            -
                            @endif
                        </span>
                    </div>

                </div>
                @else
                <div class="text-center py-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="mt-2 text-gray-600">Belum ada struk pengeluaran terbaru tercatat.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Daftar Barang -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                            clip-rule="evenodd" />
                    </svg>
                    Daftar Pemasukan Barang
                </h2>
                <form action="{{ route('dashboard') }}" method="GET"
                    class="mt-3 md:mt-0 flex-shrink-0 w-full md:w-auto md:inline-flex space-x-2">
                    <!-- Pencarian -->
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="flex-grow md:w-64 border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent text-sm"
                        placeholder="Cari nama barang...">

                    <!-- Sortir -->
                    <select name="sort"
                        class="border border-gray-300 px-3 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Sortir berdasarkan</option>
                        <option value="tanggal_desc" {{ request('sort') == 'tanggal_desc' ? 'selected' : '' }}>Tanggal
                            Terbaru</option>
                        <option value="tanggal_asc" {{ request('sort') == 'tanggal_asc' ? 'selected' : '' }}>Tanggal
                            Terlama</option>
                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A
                        </option>
                    </select>

                    <!-- Tombol -->
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>
                </form>

            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Barang</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomor Struk</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($barangList as $nama => $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['jumlah'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['nomor_struk'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['tanggal'] ? \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-sm text-gray-500">Tidak ada data barang masuk.</td>
                        </tr>


                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center py-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2">Tidak ada data barang ditemukan.</p>
                                    <p class="text-xs text-gray-400">Silakan ubah pencarian atau periksa kembali data
                                        pemasukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $barangList->links('vendor.pagination.custom') }}
                <div class="showing-results mt-2 text-sm text-gray-600 text-center">
                    Menampilkan {{ $barangList->firstItem() }} sampai {{ $barangList->lastItem() }} dari
                    {{ $barangList->total() }} hasil
                </div>
            </div>

        </div>

        <!-- Item Pengeluaran Terbaru -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mt-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V7h2v2z"
                            clip-rule="evenodd" />
                    </svg>
                    Daftar Pengeluaran Barang
                </h2>
                <<<<<<< HEAD
                    <form action="{{ route('dashboard') }}" method="GET" class="mt-3 md:mt-0 flex-shrink-0 w-full md:w-auto md:inline-flex space-x-2">

                    =======
                    <form action="{{ route('dashboard') }}" method="GET"
                        class="mt-3 md:mt-0 flex-shrink-0 w-full md:w-auto md:inline-flex space-x-2">
                        >>>>>>> 037a7172e078f734a3a849182961ee2343a2e024
                        <input type="text" name="search_pengeluaran" value="{{ request('search_pengeluaran') }}"
                            class="flex-grow md:w-64 border border-red-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-transparent text-sm"
                            placeholder="Cari nama barang pengeluaran...">

                        <select name="sort_pengeluaran"
                            class="border border-red-300 px-3 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                            <option value="">Sortir berdasarkan</option>
                            <option value="tanggal_desc"
                                {{ request('sort_pengeluaran') == 'tanggal_desc' ? 'selected' : '' }}>Tanggal Terbaru
                            </option>
                            <option value="tanggal_asc"
                                {{ request('sort_pengeluaran') == 'tanggal_asc' ? 'selected' : '' }}>Tanggal Terlama
                            </option>
                            <option value="nama_asc" {{ request('sort_pengeluaran') == 'nama_asc' ? 'selected' : '' }}>Nama
                                A-Z</option>
                            <option value="nama_desc" {{ request('sort_pengeluaran') == 'nama_desc' ? 'selected' : '' }}>
                                Nama Z-A</option>
                        </select>

                        <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>

                    </form>
            </div>
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Barang</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomor Struk</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pengeluaranBarangList as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item['nama_barang'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['jumlah'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item['nomor_struk'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['tanggal'] ? \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-3 text-gray-600 font-medium">Tidak ada item pengeluaran ditemukan.</p>
                                    <p class="text-xs text-gray-400">Silakan ubah pencarian atau periksa kembali data
                                        pengeluaran.</p>
                                </div>
                            </td>
                        </tr>

                        @endforelse
                    </tbody>

                </table>
            </div>
            <div class="mt-4">
                {{ $pengeluaranBarangList->links('vendor.pagination.custom') }}
                <div class="showing-results mt-2 text-sm text-gray-600 text-center">
                    Menampilkan {{ $pengeluaranBarangList->firstItem() }} sampai
                    {{ $pengeluaranBarangList->lastItem() }} dari {{ $pengeluaranBarangList->total() }} hasil
                </div>
            </div>
        </div>

        <<<<<<< HEAD=======<!-- History Rekap Barang -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v2h2V7zm0 4H9v4h2v-4z"
                            clip-rule="evenodd" />
                    </svg>
                    History Rekap Barang
                </h2>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Masuk</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Keluar</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($historyBarang as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item['nama_barang'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['jumlah'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['tanggal_masuk'] ? \Carbon\Carbon::parse($item['tanggal_masuk'])->format('d-m-Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['tanggal_keluar'] ? \Carbon\Carbon::parse($item['tanggal_keluar'])->format('d-m-Y') : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="mt-3 text-gray-600 font-medium">Tidak ada history barang ditemukan.</p>
                                        <p class="text-xs text-gray-400">Belum ada data masuk atau keluar yang terekam.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($historyBarang->total() > 0)
                <div class="mt-4">
                    {{ $historyBarang->links('vendor.pagination.custom') }}
                    <div class="showing-results mt-2 text-sm text-gray-600 text-center">
                        Menampilkan {{ $historyBarang->firstItem() }} sampai {{ $historyBarang->lastItem() }} dari
                        {{ $historyBarang->total() }} hasil
                    </div>
                </div>
                @endif
            </div>
            >>>>>>> 037a7172e078f734a3a849182961ee2343a2e024
    </div>
</div>
@endsection

<style>
    /* Custom Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }

    .pagination li {
        margin: 0 0.25rem;
    }

    .pagination li a,
    .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .pagination li a {
        color: #4b5563;
        background-color: #f3f4f6;
        border: 1px solid #e5e7eb;
    }

    .pagination li a:hover {
        background-color: #e5e7eb;
        color: #1f2937;
    }

    .pagination li.active span {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .pagination li.disabled span {
        color: #9ca3af;
        background-color: #f3f4f6;
        border-color: #e5e7eb;
        cursor: not-allowed;
    }

    /* Showing results text */
    .showing-results {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 1rem;
        text-align: center;
    }
</style>