    @extends('layouts.app')

    @section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center py-4 px-2 sm:px-4 lg:px-8">
        <div class="w-full max-w-7xl space-y-6">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Dashboard Analisis</h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600">Selamat datang kembali, <span
                        class="font-medium text-blue-600">{{ Auth::user()->name ?? 'Pengguna' }}</span>!</p>
            </div>

            <!-- Filter Section -->
            <div class="flex justify-center mb-6">
                <div class="bg-white shadow-md rounded-xl px-4 py-4 flex flex-col gap-4 border border-gray-100 w-full max-w-2xl">
                    <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-center w-full">
                        <div class="flex flex-col md:flex-row md:items-center gap-2">
                            <label class="text-sm text-gray-600 font-medium">Periode:</label>
                            <select name="time_period"
                                class="border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm font-semibold bg-gray-50 w-full md:w-auto">
                                <option value="daily" {{ request('time_period') == 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="weekly" {{ request('time_period') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                                <option value="monthly" {{ request('time_period') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="all" {{ !request('time_period') || request('time_period') == 'all' ? 'selected' : '' }}>Semua</option>
                            </select>
                        </div>
                        <div id="bulan-tahun-filter"
                            class="{{ (request('time_period') == 'monthly' || $timePeriod == 'monthly') ? '' : 'hidden' }} flex flex-col md:flex-row gap-2">
                            <select name="bulan" class="border border-gray-300 rounded-md px-2 py-1 text-sm bg-gray-50 font-semibold w-full md:w-auto">
                                @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ request('bulan', $bulan) == $month ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                </option>
                                @endforeach
                            </select>
                            <select name="tahun" class="border border-gray-300 rounded-md px-2 py-1 text-sm bg-gray-50 font-semibold w-full md:w-auto">
                                @foreach(range(now()->year, now()->year - 5) as $year)
                                <option value="{{ $year }}" {{ request('tahun', $tahun) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm flex items-center gap-2 font-semibold w-full md:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Terapkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center text-gray-500 text-sm mb-4">
                <span>
                    Filter ini akan mempengaruhi seluruh data statistik dan tabel di bawah.
                </span>
            </div>

            @if($timePeriod != 'all')
            <div class="text-sm text-gray-500 mt-2 text-center">
                Menampilkan data
                <span class="font-medium">
                    @if($timePeriod == 'daily')
                    hari ini ({{ now()->format('d M Y') }})
                    @elseif($timePeriod == 'weekly')
                    minggu ini ({{ now()->startOfWeek()->format('d M') }} - {{ now()->endOfWeek()->format('d M Y') }})
                    @elseif($timePeriod == 'monthly')
                    bulan {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}
                    @endif
                </span>
            </div>
            @endif
            
            <div class="text-sm text-gray-500 mt-2 text-center flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Menampilkan data...
            </div>

            <!-- Info Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div
                    class="bg-white p-3 sm:p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-blue-50 text-blue-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Total SPK</p>
                            <p class="text-lg sm:text-xl font-semibold text-gray-800">{{ $totalStruk ?? '0' }}</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white p-3 sm:p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-green-50 text-green-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Total Barang Masuk</p>
                            <p class="text-lg sm:text-xl font-semibold text-gray-800">{{ $totalBarangMasuk ?? '0' }}</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white p-3 sm:p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-red-50 text-red-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8 8V4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Total Barang Keluar</p>
                            <p class="text-lg sm:text-xl font-semibold text-gray-800">{{ $totalBarangKeluar ?? '0' }}</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white p-3 sm:p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-purple-50 text-purple-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Hari Ini</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-800">{{ now()->format('d-m-Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Struk Terbaru & Struk Pengeluaran Terbaru (Side by Side) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Struk Terbaru -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-2 5a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            Struk Pemasukan Terbaru
                        </h2>
                        <span class="text-xs px-1 py-0.5 bg-blue-100 text-blue-800 rounded-full">Update Terkini</span>
                    </div>

                    @if (isset($latestStruk) && $latestStruk)
                    @php
                        $items = is_string($latestStruk->items ?? null) ? json_decode($latestStruk->items, true) :
                        ($latestStruk->items ?? []);
                        $firstItem = collect($items)->first();
                        $namaBarang = $firstItem['nama'] ?? '-';
                    @endphp
                    <div class="space-y-2 text-gray-700 text-sm">
                        <div class="flex items-center">
                            <span class="w-20 text-gray-500">Nama Toko:</span>
                            <span class="font-medium truncate">{{ $latestStruk->nama_toko }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-gray-500">No Struk:</span>
                            <span class="font-mono truncate">{{ $latestStruk->nomor_struk }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-gray-500">Tanggal:</span>
                            <span class="truncate">{{ $latestStruk->tanggal_struk }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-gray-500">Nama Barang:</span>
                            <span class="font-medium truncate">{{ $barangMaster[$namaBarang] ?? $namaBarang }}</span>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-1 text-gray-600 text-xs">Belum ada SPK terbaru tercatat.</p>
                    </div>
                    @endif
                </div>

                <!-- Struk Pengeluaran Terbaru -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V7h2v2z"
                                    clip-rule="evenodd" />
                            </svg>
                            Struk Pengeluaran Terbaru
                        </h2>
                        <span class="text-xs px-1 py-0.5 bg-red-100 text-red-800 rounded-full">Update Terkini</span>
                    </div>

                    @if (isset($latestPengeluaranStruk) && $latestPengeluaranStruk)
                    @php
                        $itemsKeluar = is_string($latestPengeluaranStruk->daftar_barang ?? null) ?
                        json_decode($latestPengeluaranStruk->daftar_barang, true) :
                        ($latestPengeluaranStruk->daftar_barang ?? []);
                        $firstItemKeluar = collect($itemsKeluar)->first();
                        $namaBarangKeluar = $firstItemKeluar['nama'] ?? '-';
                    @endphp
                    <div class="space-y-2 text-gray-700 text-sm">
                        <div class="flex items-center">
                            <span class="w-20 text-gray-500">Nama Spk:</span>
                            <span class="font-medium truncate">{{ $latestPengeluaranStruk->nama_toko ?? '-' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-gray-500">No Spk :</span>
                            <span class="font-mono truncate">{{ $latestPengeluaranStruk->nomor_struk }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-gray-500">Tanggal:</span>
                            <span class="truncate">{{ $latestPengeluaranStruk->tanggal ?? '-' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-20 text-gray-500">Nama Barang:</span>
                            <span class="font-medium truncate">{{ $barangMaster[$namaBarangKeluar] ?? $namaBarangKeluar }}</span>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-1 text-gray-600 text-xs">Belum ada SPK pengeluaran terbaru tercatat.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Daftar Barang -->
            <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center mb-2 sm:mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                clip-rule="evenodd" />
                        </svg>
                        Daftar Pemasukan Barang
                    </h2>
                    <form action="{{ route('dashboard') }}" method="GET"
                        class="mt-2 sm:mt-0 flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="flex-grow border border-gray-300 px-2 py-1 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 text-sm w-full sm:w-40"
                            placeholder="Cari nama barang...">
                        <select name="sort"
                            class="border border-gray-300 px-2 py-1 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 w-full sm:w-40">
                            <option value="">Sortir berdasarkan</option>
                            <option value="tanggal_desc" {{ request('sort') == 'tanggal_desc' ? 'selected' : '' }}>Tanggal Terbaru</option>
                            <option value="tanggal_asc" {{ request('sort') == 'tanggal_asc' ? 'selected' : '' }}>Tanggal Terlama</option>
                            <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                            <option value="status_asc" {{ request('sort') == 'status_asc' ? 'selected' : '' }}>Status (A-Z)</option>
                            <option value="status_desc" {{ request('sort') == 'status_desc' ? 'selected' : '' }}>Status (Z-A)</option>
                        </select>
                        <button type="submit"
                            class="bg-blue-600 text-white px-2 py-1 rounded-md hover:bg-blue-700 text-sm w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden">
                    @forelse ($pemasukans as $pemasukan)
                    @php
                        $items = is_string($pemasukan->items) 
                            ? json_decode($pemasukan->items, true) 
                            : ($pemasukan->items ?? []);
                        $firstItem = collect($items)->first();
                        $namaBarang = $firstItem['nama'] ?? '-';
                        $totalJumlah = collect($items)->sum('jumlah');
                        $status = $pemasukan->status ?? 'progress';
                        $statusColors = [
                            'completed' => 'bg-green-100 text-green-800',
                            'progress' => 'bg-yellow-100 text-yellow-800'
                        ];
                        $statusTexts = [
                            'completed' => 'Completed',
                            'progress' => 'Progress'
                        ];
                        $colorClass = $statusColors[$status] ?? $statusColors['progress'];
                        $statusText = $statusTexts[$status] ?? 'Progress';
                    @endphp
                    <div class="bg-gray-50 p-3 rounded-lg mb-2 border pemasukan-card {{ strtolower($status) }}" data-status="{{ strtolower($status) }}">
                        <div class="flex justify-between items-start">
                            <div class="font-medium text-gray-900 truncate">{{ $barangMaster[$namaBarang] ?? $namaBarang }}</div>
                            <span class="px-1 py-0.5 text-xs font-medium rounded-full {{ $colorClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-600 grid grid-cols-2 gap-1">
                            <div>Jumlah: <span class="font-medium">{{ $totalJumlah ?? '0' }}</span></div>
                            <div>No Struk: <span class="font-medium">{{ $pemasukan->nomor_struk ?? '-' }}</span></div>
                            <div class="col-span-2">Tanggal: <span class="font-medium">{{ $pemasukan->tanggal_struk ? \Carbon\Carbon::parse($pemasukan->tanggal_struk)->format('d-m-Y') : '-' }}</span></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-1 text-gray-600 text-xs">Tidak ada data pemasukan.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block border border-gray-200 rounded-lg overflow-x-auto">
                    <table class="w-full text-xs min-w-full" id="pemasukanTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">Nomor Struk</th>
                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                                <th class="px-2 py-1 text-left font-medium text-gray-500 uppercase tracking-wider">
                                    <select id="statusFilterPemasukan" class="border border-gray-300 rounded-md px-1 py-0.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300 w-full">
                                        <option value="">Semua Status</option>
                                        <option value="progress">Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pemasukans as $pemasukan)
                            @php
                                $items = is_string($pemasukan->items) 
                                    ? json_decode($pemasukan->items, true) 
                                    : ($pemasukan->items ?? []);
                                $firstItem = collect($items)->first();
                                $namaBarang = $firstItem['nama'] ?? '-';
                                $totalJumlah = collect($items)->sum('jumlah');
                                $status = $pemasukan->status ?? 'progress';
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'progress' => 'bg-yellow-100 text-yellow-800'
                                ];
                                $statusTexts = [
                                    'completed' => 'Completed',
                                    'progress' => 'Progress'
                                ];
                                $colorClass = $statusColors[$status] ?? $statusColors['progress'];
                                $statusText = $statusTexts[$status] ?? 'Progress';
                            @endphp
                            <tr class="pemasukan-row {{ strtolower($status) }}" data-status="{{ strtolower($status) }}">
                                <td class="px-2 py-1 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-2 py-1 font-medium text-gray-900">{{ $barangMaster[$namaBarang] ?? $namaBarang }}</td>
                                <td class="px-2 py-1">{{ $totalJumlah ?? '0' }}</td>
                                <td class="px-2 py-1">{{ $pemasukan->nomor_struk ?? '-' }}</td>
                                <td class="px-2 py-1">
                                    {{ $pemasukan->tanggal_struk ? \Carbon\Carbon::parse($pemasukan->tanggal_struk)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="px-2 py-1">
                                    <span class="px-1 py-0.5 text-xs font-medium rounded-full {{ $colorClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-2 py-2 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">
                    {{ $pemasukans->links('vendor.pagination.custom') }}
                    <div class="showing-results mt-1 text-xs text-gray-600 text-center">
                        Menampilkan {{ $pemasukans->firstItem() }} sampai {{ $pemasukans->lastItem() }} dari
                        {{ $pemasukans->total() }} hasil
                    </div>
                </div>
            </div>

            <!-- Item Pengeluaran -->
            <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm border border-gray-100 mt-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center mb-2 sm:mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V7h2v2z"
                                clip-rule="evenodd" />
                        </svg>
                        Daftar Pengeluaran Barang
                    </h2>
                    <form action="{{ route('dashboard') }}" method="GET"
                        class="mt-2 sm:mt-0 flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                        <input type="text" name="search_pengeluaran" value="{{ request('search_pengeluaran') }}"
                            class="flex-grow border border-red-300 px-2 py-1 rounded-md focus:outline-none focus:ring-2 focus:ring-red-300 text-sm w-full sm:w-40"
                            placeholder="Cari nama barang pengeluaran...">
                        <select name="sort_pengeluaran"
                            class="border border-red-300 px-2 py-1 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-red-300 w-full sm:w-40">
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
                            class="bg-red-600 text-white px-2 py-1 rounded-md hover:bg-red-700 text-sm w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden">
                    @forelse ($pengeluarans as $pengeluaran)
                    @php
                        $items = is_string($pengeluaran->daftar_barang) 
                            ? json_decode($pengeluaran->daftar_barang, true) 
                            : $pengeluaran->daftar_barang;
                        $firstItem = collect($items)->first();
                        $namaBarang = $firstItem['nama'] ?? '-';
                        $totalJumlah = collect($items)->sum('jumlah');
                        $status = $pengeluaran->status ?? 'progress';
                        $statusColors = [
                            'completed' => 'bg-green-100 text-green-800',
                            'progress' => 'bg-yellow-100 text-yellow-800'
                        ];
                        $statusTexts = [
                            'completed' => 'Completed',
                            'progress' => 'Progress'
                        ];
                        $colorClass = $statusColors[$status] ?? $statusColors['progress'];
                        $statusText = $statusTexts[$status] ?? 'Progress';
                    @endphp
                    <div class="bg-gray-50 p-3 rounded-lg mb-2 border pengeluaran-card {{ strtolower($status) }}" data-status="{{ strtolower($status) }}">
                        <div class="flex justify-between items-start">
                            <div class="font-medium text-gray-900 truncate">{{ $barangMaster[$namaBarang] ?? $namaBarang }}</div>
                            <span class="px-1 py-0.5 text-xs font-medium rounded-full {{ $colorClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-600 grid grid-cols-2 gap-1">
                            <div>Pegawai: <span class="font-medium">{{ $pengeluaran->pegawai->nama ?? '-' }}</span></div>
                            <div>Jumlah: <span class="font-medium">{{ $totalJumlah ?? '0' }}</span></div>
                            <div>No SPK: <span class="font-medium">{{ $pengeluaran->nomor_struk ?? '-' }}</span></div>
                            <div>Tanggal: <span class="font-medium">{{ $pengeluaran->tanggal ? \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d-m-Y') : '-' }}</span></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-1 text-gray-600 text-xs">Tidak ada data pengeluaran.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block border border-gray-200 rounded-lg overflow-x-auto">
                    <table class="w-full text-xs min-w-full" id="pengeluaranTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pegawai</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nomor SPK</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Keluar</th>
                                <th scope="col"
                                    class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <select id="statusFilterPengeluaran" class="border border-red-300 rounded-md px-1 py-0.5 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 w-full">
                                        <option value="">Semua Status</option>
                                        <option value="progress">Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($pengeluarans as $pengeluaran)
                            @php
                                $items = is_string($pengeluaran->daftar_barang) 
                                    ? json_decode($pengeluaran->daftar_barang, true) 
                                    : $pengeluaran->daftar_barang;
                                $firstItem = collect($items)->first();
                                $namaBarang = $firstItem['nama'] ?? '-';
                                $totalJumlah = collect($items)->sum('jumlah');
                                $status = $pengeluaran->status ?? 'progress';
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'progress' => 'bg-yellow-100 text-yellow-800'
                                ];
                                $statusTexts = [
                                    'completed' => 'Completed',
                                    'progress' => 'Progress'
                                ];
                                $colorClass = $statusColors[$status] ?? $statusColors['progress'];
                                $statusText = $statusTexts[$status] ?? 'Progress';
                            @endphp
                            <tr class="pengeluaran-row {{ strtolower($status) }}" data-status="{{ strtolower($status) }}">
                                <td class="px-2 py-1 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-2 py-1 font-medium text-gray-900">{{ $pengeluaran->pegawai->nama ?? '-' }}</td>
                                <td class="px-2 py-1">{{ $pengeluaran->nomor_struk ?? '-' }}</td>
                                <td class="px-2 py-1">{{ $barangMaster[$namaBarang] ?? $namaBarang }}</td>
                                <td class="px-2 py-1">{{ $totalJumlah ?? '0' }}</td>
                                <td class="px-2 py-1">
                                    {{ $pengeluaran->tanggal ? \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="px-2 py-1">
                                    <span class="px-1 py-0.5 text-xs font-medium rounded-full {{ $colorClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-2 py-2 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-2">
                    {{ $pengeluarans->links('vendor.pagination.custom') }}
                    <div class="showing-results mt-1 text-xs text-gray-600 text-center">
                        Menampilkan {{ $pengeluarans->firstItem() }} sampai
                        {{ $pengeluarans->lastItem() }} dari {{ $pengeluarans->total() }} hasil
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0.75rem 0;
        }

        .pagination li {
            margin: 0 0.125rem;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .pagination li a {
            border: 1px solid #e5e7eb;
            color: #4b5563;
            background-color: #f3f4f6;
        }

        .pagination li a:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }

        .pagination li.disabled span {
            background-color: #f3f4f6;
            border-color: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .pagination li.active span {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .showing-results {
            color: #6b7280;
            font-size: 0.75rem;
            margin-top: 0.5rem;
            text-align: center;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        th, td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 4px 6px;
            font-size: 0.85rem;
        }

        @media (max-width: 767px) {
            .pemasukan-card, .pengeluaran-card {
                display: block;
            }
            
            .pemasukan-row, .pengeluaran-row {
                display: none;
            }
            
            th, td {
                max-width: none;
                font-size: 0.75rem;
                padding: 2px 4px;
            }
        }
        
        @media (min-width: 768px) {
            .pemasukan-card, .pengeluaran-card {
                display: none;
            }
            
            .pemasukan-row, .pengeluaran-row {
                display: table-row;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timePeriod = document.querySelector('select[name="time_period"]');
            const bulanTahun = document.getElementById('bulan-tahun-filter');
            if (timePeriod) {
                timePeriod.addEventListener('change', function() {
                    if (this.value === 'monthly') {
                        bulanTahun.classList.remove('hidden');
                    } else {
                        bulanTahun.classList.add('hidden');
                    }
                });
            }

            // Real-time status filter for Pemasukan
            const statusFilterPemasukan = document.getElementById('statusFilterPemasukan');
            const pemasukanRows = document.querySelectorAll('#pemasukanTable .pemasukan-row');
            const pemasukanCards = document.querySelectorAll('.pemasukan-card');
            
            statusFilterPemasukan.addEventListener('change', function() {
                const selectedStatus = this.value.toLowerCase();
                filterElements(pemasukanRows, selectedStatus);
                filterElements(pemasukanCards, selectedStatus);
            });

            // Real-time status filter for Pengeluaran
            const statusFilterPengeluaran = document.getElementById('statusFilterPengeluaran');
            const pengeluaranRows = document.querySelectorAll('#pengeluaranTable .pengeluaran-row');
            const pengeluaranCards = document.querySelectorAll('.pengeluaran-card');
            
            statusFilterPengeluaran.addEventListener('change', function() {
                const selectedStatus = this.value.toLowerCase();
                filterElements(pengeluaranRows, selectedStatus);
                filterElements(pengeluaranCards, selectedStatus);
            });
            
            function filterElements(elements, selectedStatus) {
                elements.forEach(element => {
                    const elementStatus = element.getAttribute('data-status');
                    if (selectedStatus === '' || elementStatus === selectedStatus) {
                        element.style.display = '';
                    } else {
                        element.style.display = 'none';
                    }
                });
            }
        });
    </script>
    @endsection