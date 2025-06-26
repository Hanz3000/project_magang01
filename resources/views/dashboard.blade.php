@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-4xl w-full space-y-8 bg-white p-8 rounded-3xl shadow-xl transition duration-300 ease-in-out transform hover:shadow-2xl border border-gray-100">

            <!-- Header Section -->
            <div class="text-center mb-6">
                <h1 class="text-4xl font-extrabold text-gray-900 leading-tight tracking-tight drop-shadow-sm">Dashboard
                    Analisis</h1>
                <p class="mt-2 text-xl text-gray-700 font-light">Selamat datang kembali, <span
                        class="font-bold text-blue-700">{{ Auth::user()->name ?? 'Pengguna' }}</span>!</p>
            </div>

            <!-- Info Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Struk Card -->
                <div
                    class="bg-gradient-to-br from-blue-100 to-blue-300 rounded-2xl p-6 shadow-lg flex flex-col items-start transform transition duration-300 hover:scale-105 hover:shadow-xl border border-blue-400 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-blue-400 rounded-full opacity-20 -mr-8 -mt-8"></div>
                    <div class="flex items-center justify-center w-16 h-16 bg-blue-800 rounded-full shadow-md mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-blue-900">Total Struk Tercatat</p>
                    <p class="mt-1 text-4xl font-extrabold text-blue-950">{{ $totalStruk ?? '0' }}</p>
                </div>

                <!-- Date Card -->
                <div
                    class="bg-gradient-to-br from-purple-100 to-purple-300 rounded-2xl p-6 shadow-lg flex flex-col items-start transform transition duration-300 hover:scale-105 hover:shadow-xl border border-purple-400 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 bg-purple-400 rounded-full opacity-20 -mr-8 -mt-8"></div>
                    <div class="flex items-center justify-center w-16 h-16 bg-purple-800 rounded-full shadow-md mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-purple-900">Tanggal Hari Ini</p>
                    <p class="mt-1 text-4xl font-extrabold text-purple-950">{{ now()->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Latest Struk Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Informasi Struk Terbaru</h2>

                @if (isset($latestStruk) && $latestStruk)
                    <div
                        class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-2xl border border-orange-300 shadow-inner">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-y-3 gap-x-6 text-gray-800">
                            <div>
                                <p class="text-lg font-medium text-orange-900 mb-1">Nama Toko:</p>
                                <p class="text-2xl font-extrabold text-orange-950">{{ $latestStruk->nama_toko }}</p>
                            </div>
                            <div>
                                <p class="text-lg font-medium text-orange-900 mb-1">Nomor Struk:</p>
                                <p class="text-2xl font-extrabold text-orange-950">{{ $latestStruk->nomor_struk }}</p>
                            </div>
                            <div>
                                <p class="text-lg font-medium text-orange-900 mb-1">Tanggal Transaksi:</p>
                                <p class="text-2xl font-extrabold text-orange-950">{{ $latestStruk->tanggal_struk }}</p>
                            </div>
                            <div class="md:col-span-2 lg:col-span-2 text-center md:text-left">
                                <p class="text-xl font-medium text-orange-900 mb-1">Total Belanja:</p>
                                {{-- Mengganti tampilan total harga dengan simbol rahasia --}}
                                <p class="text-5xl font-extrabold text-emerald-700">Rp*******</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 p-6 rounded-2xl border border-blue-200 shadow-inner">
                        <p class="text-gray-700 text-center text-xl font-medium py-4">Belum ada struk terbaru yang tercatat.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Barang List Table -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                    <h2 class="text-3xl font-bold text-gray-900">Daftar Barang Belanja</h2>
                    <form action="{{ route('dashboard') }}" method="GET" class="flex w-full md:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="flex-grow border border-gray-300 rounded-l-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base text-gray-800 transition duration-300 placeholder-gray-500"
                            placeholder="Cari nama barang...">
                        <button type="submit"
                            class="bg-blue-700 text-white px-5 py-2 rounded-r-xl hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 text-base font-semibold">
                            Cari
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                    <table class="min-w-full text-base text-left text-gray-700 divide-y divide-gray-200">
                        <thead class="bg-gray-100 uppercase text-gray-800 font-bold text-base">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-center">No</th>
                                <th scope="col" class="px-6 py-4">Nama Barang</th>
                                <th scope="col" class="px-6 py-4 text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($barangList ?? [] as $nama => $jumlah)
                                <tr class="bg-white hover:bg-blue-50 transition duration-200 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $nama }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-bold text-blue-700">
                                        {{ $jumlah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-gray-600 text-lg font-medium">Tidak
                                        ada data barang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
