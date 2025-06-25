@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Dashboard</h1>
            <p class="text-gray-600">Selamat datang kembali, {{ Auth::user()->name }}</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Struk Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Struk</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalStruk }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Harga Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-gray-900">Rp{{ number_format($totalHarga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Date Card -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ now()->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Latest Receipt -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Struk Terbaru
                        </h2>
                        <span class="bg-orange-100 text-orange-800 text-xs px-2.5 py-0.5 rounded-full">
                            Terbaru
                        </span>
                    </div>

                    @if ($latestStruk)
                    <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-orange-600 font-medium uppercase tracking-wider">Toko</p>
                                    <p class="font-semibold">{{ $latestStruk->nama_toko }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-orange-600 font-medium uppercase tracking-wider">Nomor Struk</p>
                                    <p class="font-mono bg-white px-2 py-1 rounded border">{{ $latestStruk->nomor_struk }}</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-orange-600 font-medium uppercase tracking-wider">Tanggal</p>
                                    <p>{{ $latestStruk->tanggal_struk }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-orange-600 font-medium uppercase tracking-wider">Total</p>
                                    <p class="text-green-600 font-bold">Rp{{ number_format($latestStruk->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">Belum ada data struk</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- User Profile -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profil Saya
                    </h2>

                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white text-2xl font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold">{{ Auth::user()->name }}</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Email</p>
                            <p class="text-sm">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Bergabung</p>
                            <p class="text-sm">{{ Auth::user()->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection