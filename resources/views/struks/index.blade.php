@extends('layouts.app')

@section('content')
{{-- Wrapper layout yang salah (min-h-screen, dll) telah dihapus --}}

    @if (session('success'))
        {{-- Script ini akan memicu notifikasi di layout app.blade.php --}}
    @endif

    <form id="bulkDeleteForm" method="POST" action="{{ route('struks.bulk-delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="selected_ids" id="selectedIds">
    </form>

    <div class="mb-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">Manajemen Struk</h1>
            <p class="text-gray-400">Kelola dan atur semua struk pemasukan</p>
        </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto items-center">
                @php
                $isPemasukan = request()->routeIs('struks.index');
                @endphp
                <div class="flex items-center gap-2 mt-2 sm:mt-0">
                    <div class="relative inline-block w-12 align-middle select-none">
                        <input type="checkbox" id="toggle-struk" class="hidden" {{ $isPemasukan ? '' : 'checked' }}>
                        <label for="toggle-struk" title="Lihat Data Pengeluaran" class="block h-6 rounded-full cursor-pointer transition-colors duration-300 ease-in-out {{ $isPemasukan ? 'bg-blue-600' : 'bg-gray-700' }}">
                            <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transform transition-transform duration-300 ease-in-out {{ $isPemasukan ? '' : 'translate-x-6' }}"></span>
                        </label>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const toggle = document.getElementById('toggle-struk');
                        if (toggle) {
                            toggle.addEventListener('change', function() {
                                if (this.checked) {
                                    window.location.href = "{{ route('pengeluarans.index') }}";
                                }
                            });
                        }
                    });
                </script>

                <div class="relative group">
                    <button class="flex items-center gap-2 px-4 py-2 bg-gray-800 text-gray-300 rounded-lg border border-gray-700 hover:bg-gray-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Ekspor
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-gray-800 rounded-md shadow-lg z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 origin-top-right border border-gray-700">
                        <a href="{{ route('struks.export.excel') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Format Excel</a>
                        <a href="{{ route('struks.export.csv') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">Format CSV</a>
                    </div>
                </div>
                
                <div id="bulkActionsContainer" class="hidden flex items-center gap-2 bg-red-900 bg-opacity-50 rounded-lg p-1 border border-red-700">
                    <span id="selectedCount" class="px-2 py-1 bg-red-800 text-red-200 text-xs font-medium rounded-md">0 dipilih</span>
                    <button onclick="confirmBulkDelete()" class="flex items-center gap-2 px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                    </button>
                    <button onclick="clearSelection()" class="p-1 text-red-400 hover:text-red-300 rounded-full hover:bg-red-800 transition-colors" title="Batal">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 rounded-xl shadow-sm border border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-lg">
        
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gray-700 rounded-lg">
                        <svg class="w-6 h-6 text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Data Struk Pemasukan</h3>
                        <p class="text-sm text-gray-400">{{ $struks->total() }} struk ditemukan</p>
                    </div>
                </div>
                <div class="relative w-64">
                    <input type="text" name="search" id="searchInput"
                        class="pl-10 pr-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 w-full transition-all bg-gray-800 text-sm text-white"
                        value="{{ request('search') }}" autocomplete="off"
                        placeholder="Cari Nama Toko atau No. Struk">
                    
                    <button type="button" class="absolute left-3 top-2.5 text-gray-500 pointer-events-none">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    @if (request('search'))
                    <button id="clearSearch" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-300"
                        title="Bersihkan pencarian">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @endif
                    <div id="searchLoading" class="hidden absolute right-10 top-2.5">
                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-400 uppercase tracking-wider w-10">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-600 bg-gray-900 text-blue-500 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left font-medium text-gray-400 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-400 uppercase tracking-wider">Nama Toko</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-400 uppercase tracking-wider">No. Struk</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-400 uppercase tracking-wider">Masuk</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-400 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-400 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-right font-medium text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-400 uppercase tracking-wider">Foto Struk</th>
                        <th class="px-6 py-3 text-center font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-700">
                    @forelse ($struks as $index => $struk)
                    @php
                    $items = is_string($struk->items) ? json_decode($struk->items, true) : $struk->items;
                    if (!is_array($items)) $items = [];
                    $totalHarga = collect($items)->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
                    @endphp

                    <tr class="even:bg-gray-800 hover:bg-gray-700 transition-colors animate-fadeIn">
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <input type="checkbox" name="selected_ids[]" value="{{ $struk->id }}" class="row-checkbox rounded border-gray-600 bg-gray-900 text-blue-500 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-100">{{ $struks->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-100">{{ $struk->nama_toko }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                            {{ $struk->nomor_struk }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-100">{{ date('d M Y', strtotime($struk->tanggal_struk)) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-2 overflow-x-auto">
                                @foreach ($items as $item)
                                <div class="flex items-start whitespace-nowrap">
                                    <span class="inline-block w-2 h-2 rounded-full bg-gray-500 mt-2 mr-2 flex-shrink-0"></span>
                                    <span class="text-gray-300">
                                        {{ $item['nama_barang'] ?? $barangList[$item['nama']]?->nama_barang ?? $item['nama'] }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs space-y-2">
                                @foreach ($items as $item)
                                <div class="text-gray-400 text-sm">
                                    <span class="whitespace-nowrap">x{{ $item['jumlah'] ?? '-' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-white">
                            Rp{{ number_format($totalHarga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $struk->status == 'progress' ? 'bg-yellow-900 text-yellow-300' : 'bg-blue-900 text-blue-300' }}">
                                {{ ucfirst($struk->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if ($struk->foto_struk)
                            <button onclick="openModal('{{ asset('storage/struk_foto/' . $struk->foto_struk) }}')" class="text-blue-500 hover:text-blue-400">
                                <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-xs text-gray-400">Lihat</span>
                            </button>
                            @else
                            <span class="text-gray-500 italic text-xs">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('struks.show', $struk->id) }}" class="text-gray-500 hover:text-gray-300 p-1 rounded-full hover:bg-gray-700 transition-colors" title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <button type="button" onclick="openEditModal({{ $struk->id }})" class="text-gray-500 hover:text-blue-400 p-1 rounded-full hover:bg-gray-700 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('struks.destroy', $struk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus struk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="openDeleteModal('{{ route('struks.destroy', $struk->id) }}')" class="text-gray-500 hover:text-red-500 p-1 rounded-full hover:bg-red-900 hover:bg-opacity-30 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="animate-fadeIn">
                        <td colspan="11" class="px-6 py-4 text-center text-gray-500">Tidak ada struk ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($struks->hasPages())
        <div class="px-6 py-4 border-t border-gray-700 flex items-center justify-between">
            <div class="text-sm text-gray-400">
                Menampilkan {{ $struks->firstItem() }} sampai {{ $struks->lastItem() }} dari {{ $struks->total() }} hasil
            </div>
            <div class="flex space-x-1">
                @if ($struks->onFirstPage())
                <span class="px-3 py-1 rounded-lg border border-gray-700 text-gray-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
                @else
                <a href="{{ $struks->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                @endif

                @foreach ($struks->getUrlRange(1, $struks->lastPage()) as $page => $url)
                @if ($page == $struks->currentPage())
                <span class="px-3 py-1 rounded-lg bg-blue-600 text-white">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="px-3 py-1 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">{{ $page }}</a>
                @endif
                @endforeach

                @if ($struks->hasMorePages())
                <a href="{{ $struks->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-700 text-gray-300 hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @else
                <span class="px-3 py-1 rounded-lg border border-gray-700 text-gray-600 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
                @endif
            </div>
        </div>
        @endif
    </div>


{{-- Modal Lihat Gambar --}}
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center">
    <div class="relative">
        <img id="modalImage" src="" class="max-h-[80vh] rounded shadow-lg select-none">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-white hover:text-gray-300 transition duration-200">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

{{-- PERBAIKAN: Modal Edit (Scroll & Warna) --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4 overflow-y-auto">
    {{-- 'my-auto' atau 'my-8' ditambahkan untuk margin vertikal saat konten pendek --}}
    <div class="relative w-full max-w-4xl mx-auto my-auto"> 
        <div class="bg-gray-800 rounded-lg shadow-xl border border-gray-700 flex flex-col max-h-[90vh]">
            {{-- HEADER MODAL (Fixed) --}}
            <div class="bg-gray-800 px-6 py-4 border-b border-gray-700 rounded-t-lg flex-shrink-0">
                <div class="flex items-center justify-between">
                    {{-- PERBAIKAN: Ikon dihapus agar lebih bersih --}}
                    <div>
                        <h3 class="text-lg font-semibold text-white">Edit Struk</h3>
                        <p class="text-sm text-gray-400">Ubah detail struk, termasuk item dan status</p>
                    </div>
                    <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- FORM WRAPPER --}}
            <form method="POST" action="" id="editForm" class="flex-1 flex flex-col min-h-0">
                @csrf
                @method('PUT')

                {{-- KONTEN MODAL (Scrollable) --}}
                <div class="px-6 py-4 flex-1 overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Nama Toko</label>
                            <input type="text" name="nama_toko" id="editNamaToko" class="w-full border border-gray-600 bg-gray-700 text-white rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Nomor Struk</label>
                            <input type="text" name="nomor_struk" id="editNomorStruk" class="w-full border border-gray-600 bg-gray-700 text-white rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Tanggal Masuk</label>
                            <input type="date" name="tanggal_struk" id="editTanggalStruk" class="w-full border border-gray-600 bg-gray-700 text-white rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" style="color-scheme: dark;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Status</label>
                            <select name="status" id="editStatus" class="w-full border border-gray-600 bg-gray-700 text-white rounded-lg px-3 py-2 mt-1 focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                <option value="progress">Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-4 pb-3 mb-4 border-b border-gray-700">
                        <div class="col-span-5">
                            <label class="block text-sm font-medium text-gray-300 text-center">Nama Barang</label>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-300 text-center">Jumlah</label>
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-medium text-gray-300 text-right">Harga Satuan</label>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-300 text-center">Aksi</label>
                        </div>
                    </div>
                    <div id="modalItemsContainer" class="space-y-3 mb-6"></div>
                    <div class="flex justify-end text-gray-200 font-medium mb-4">
                        <span id="modalTotalPrice">Total: Rp0</span>
                    </div>

                    <div class="border-2 border-dashed border-gray-600 rounded-lg p-4 bg-gray-900 mb-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-gray-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="text-sm font-medium text-gray-100">Tambah Item Baru</span>
                        </div>
                        <div class="grid grid-cols-12 gap-4 items-end">
                            <div class="col-span-5 relative">
                                <input type="text" class="item-search w-full border border-gray-600 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-gray-500 focus:border-gray-500 bg-gray-700 text-white" id="modalNewItemNama" placeholder="Cari barang atau pilih dari daftar..." autocomplete="off" readonly onclick="toggleBarangDropdown()">
                                <div class="absolute z-10 w-full mt-1 bg-gray-700 border border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden" id="barangDropdown">
                                    <div class="sticky top-0 bg-gray-700 p-2 border-b border-gray-600">
                                        <input type="text" id="filterBarang" class="w-full px-3 py-2 border border-gray-600 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500 bg-gray-800 text-white" placeholder="Filter barang...">
                                    </div>
                                    <div class="divide-y divide-gray-600" id="barangListContainer">
                                        @foreach($barangList as $barang)
                                        <div class="px-4 py-3 hover:bg-gray-600 cursor-pointer flex justify-between items-center barang-item" data-kode="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}" data-harga="{{ $barang->harga }}">
                                            <span>
                                                <span class="font-mono text-xs text-gray-400">{{ $barang->kode_barang }}</span>
                                                <span class="ml-2 text-gray-200">{{ $barang->nama_barang }}</span>
                                            </span>
                                            @if($barang->harga > 0)
                                            <span class="text-xs text-gray-400">Rp{{ number_format($barang->harga, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <input type="number" class="w-full border border-gray-600 bg-gray-700 text-white rounded-lg px-3 py-2.5 text-center focus:ring-2 focus:ring-gray-500 focus:border-gray-500" placeholder="Jumlah" id="modalNewItemJumlah" min="1">
                            </div>
                            <div class="col-span-3">
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                                    <input type="number" class="w-full border border-gray-600 bg-gray-700 text-white rounded-lg pl-8 pr-3 py-2.5 text-right focus:ring-2 focus:ring-gray-500 focus:border-gray-500" placeholder="0" id="modalNewItemHarga" min="0">
                                </div>
                            </div>
                            <div class="col-span-2 flex justify-center">
                                <button type="button" id="addItemBtn" class="px-4 py-2.5 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium flex items-center gap-2 shadow-sm" onclick="addNewItemToModal()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div> {{-- Akhir dari div.flex-1.overflow-y-auto --}}

                {{-- FOOTER MODAL (Fixed) --}}
                <div class="px-6 py-4 flex justify-end space-x-3 border-t border-gray-700 flex-shrink-0">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors font-medium">
                        Batal
                    </button>
                    <button type="button" onclick="submitEditForm()" class="px-6 py-2.5 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition-colors font-medium shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form> {{-- Akhir dari tag Form --}}
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-700">
            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modalTitle">Konfirmasi Penghapusan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-400">Apakah Anda yakin ingin menghapus struk ini?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-700">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-gray-200 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulkActionsContainer');
        const selectedCount = document.getElementById('selectedCount');
        const selectedIdsInput = document.getElementById('selectedIds');

        function updateBulkActions() {
            const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);

            if (selectedCheckboxes.length > 0) {
                if(bulkActions) bulkActions.classList.remove('hidden');
                if(selectedCount) selectedCount.textContent = `${selectedCheckboxes.length} dipilih`;
                if(selectedIdsInput) selectedIdsInput.value = selectedIds.join(',');
                if(selectAll) {
                    selectAll.checked = selectedCheckboxes.length === rowCheckboxes.length;
                    selectAll.indeterminate = selectedCheckboxes.length > 0 && selectedCheckboxes.length < rowCheckboxes.length;
                }
            } else {
                if(bulkActions) bulkActions.classList.add('hidden');
                if(selectedIdsInput) selectedIdsInput.value = '';
                if(selectAll) {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                }
            }
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                const isChecked = this.checked;
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                updateBulkActions();
            });
        }

        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
        const searchLoading = document.getElementById('searchLoading');
        let searchTimeout;

        function loadData(searchTerm = '') {
            const url = new URL(window.location.href);
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.delete('page');

            if(searchLoading) searchLoading.classList.remove('hidden');

            fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newTableBody = doc.querySelector('tbody');
                    const currentTableBody = document.querySelector('tbody');
                    if (newTableBody && currentTableBody) {
                        currentTableBody.innerHTML = newTableBody.innerHTML;
                    }
                    
                    const newPaginationContainer = doc.querySelector('.flex.space-x-1');
                    const currentPaginationContainer = document.querySelector('.flex.space-x-1');
                    if (newPaginationContainer && currentPaginationContainer) {
                        currentPaginationContainer.parentElement.innerHTML = newPaginationContainer.parentElement.innerHTML;
                    } else if (currentPaginationContainer) {
                        currentPaginationContainer.parentElement.innerHTML = '';
                    }

                    const newResultsInfo = doc.querySelector('.text-sm.text-gray-400');
                    const currentResultsInfo = document.querySelector('.text-sm.text-gray-400');
                    if (newResultsInfo && currentResultsInfo) {
                        currentResultsInfo.textContent = newResultsInfo.textContent;
                    } else if (currentResultsInfo) {
                        currentResultsInfo.textContent = '0 hasil ditemukan';
                    }
                    
                    if(searchLoading) searchLoading.classList.add('hidden');
                    animateRows();
                    
                    const newRowCheckboxes = document.querySelectorAll('.row-checkbox');
                    newRowCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', updateBulkActions);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    if(searchLoading) searchLoading.classList.add('hidden');
                });
        }

        function animateRows() {
            document.querySelectorAll('tbody tr').forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(10px)';
                row.style.transition = 'all 0.3s ease-out';
                row.style.transitionDelay = `${index * 0.05}s`;
                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, 50);
            });
        }
        
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                const cursorPosition = this.selectionStart;
                localStorage.setItem('searchCursorPosition', cursorPosition);
                searchTimeout = setTimeout(() => {
                    loadData(searchTerm);
                    const url = new URL(window.location.href);
                    if (searchTerm) {
                        url.searchParams.set('search', searchTerm);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.history.pushState({}, '', url.toString());
                }, 500);
            });
        }

        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                localStorage.removeItem('searchCursorPosition');
                loadData('');
                const url = new URL(window.location.href);
                url.searchParams.delete('search');
                window.history.pushState({}, '', url.toString());
            });
        }

        window.addEventListener('popstate', function() {
            const url = new URL(window.location.href);
            const searchTerm = url.searchParams.get('search') || '';
            if(searchInput) {
                searchInput.value = searchTerm;
            }
            loadData(searchTerm);
        });

        if(searchInput) {
            const savedCursorPosition = localStorage.getItem('searchCursorPosition');
            if (searchInput.value && savedCursorPosition) {
                setTimeout(() => {
                    searchInput.focus();
                    searchInput.selectionStart = searchInput.selectionEnd = parseInt(savedCursorPosition);
                    localStorage.removeItem('searchCursorPosition');
                }, 50);
            }
        }

        animateRows();
        initBarangDropdown();
    });

    function clearSelection() {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.checked = false;
        }
        document.getElementById('bulkActionsContainer').classList.add('hidden');
        document.getElementById('selectedIds').value = '';
    }

    function confirmBulkDelete() {
        const selectedIds = document.getElementById('selectedIds').value;
        if (!selectedIds) {
            Swal.fire({
                title: 'Tidak Ada Item Terpilih',
                text: 'Pilih setidaknya satu struk untuk dihapus.',
                icon: 'warning',
                background: '#1F2937',
                color: '#F9FAFB'
            });
            return;
        }
        const count = selectedIds.split(',').length;
        confirmDelete({ target: document.getElementById('bulkDeleteForm') }, `${count} struk`);
    }

    function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function openDeleteModal(formAction) {
        document.getElementById('deleteForm').action = formAction;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    let currentEditStrukId = null;

    function openEditModal(strukId) {
        currentEditStrukId = strukId;
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('editForm').action = `/struks/${strukId}`;
        modal.scrollTop = 0;

        const struk = window.strukItemsData[strukId];
        if (!struk) {
            console.error('Data struk tidak ditemukan!');
            return;
        }

        document.getElementById('editNamaToko').value = struk.nama_toko;
        document.getElementById('editNomorStruk').value = struk.nomor_struk;
        
        const tanggal = new Date(struk.tanggal_struk);
        const tanggalFormatted = tanggal.toISOString().split('T')[0];
        document.getElementById('editTanggalStruk').value = tanggalFormatted;

        document.getElementById('editStatus').value = struk.status;

        let items = struk.items;
        if (typeof items === 'string') {
            items = JSON.parse(items);
        }
        if (!Array.isArray(items)) {
            items = [];
        }

        const container = document.getElementById('modalItemsContainer');
        container.innerHTML = '';
        let totalHarga = 0;
        items.forEach((item, index) => {
            addItemRowToModal(item, index);
            totalHarga += (parseFloat(item.jumlah) || 0) * (parseFloat(item.harga) || 0);
        });

        document.getElementById('modalTotalPrice').textContent = `Total: Rp${totalHarga.toLocaleString('id-ID')}`;
        
        let totalHargaInput = document.getElementById('editTotalHarga');
        if (!totalHargaInput) {
            totalHargaInput = document.createElement('input');
            totalHargaInput.type = 'hidden';
            totalHargaInput.name = 'total_harga';
            totalHargaInput.id = 'editTotalHarga';
            document.getElementById('editForm').appendChild(totalHargaInput);
        }
        totalHargaInput.value = totalHarga;

        initBarangDropdown();
        setTimeout(() => {
            document.getElementById('editNamaToko').focus();
        }, 100);
    }


    window.strukItemsData = {
        @foreach($struks as $struk)
            {{ $struk->id }}: {!! json_encode($struk) !!},
        @endforeach
    };

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('modalItemsContainer').innerHTML = '';
        clearNewItemFields();
    }

    function updateModalTotalPrice() {
        const container = document.getElementById('modalItemsContainer');
        let total = 0;
        container.querySelectorAll('.item-row').forEach(row => {
            const jumlah = parseFloat(row.querySelector('input[name="jumlah[]"]').value) || 0;
            const harga = parseFloat(row.querySelector('input[name="harga[]"]').value) || 0;
            total += jumlah * harga;
        });
        document.getElementById('modalTotalPrice').textContent = `Total: Rp${total.toLocaleString('id-ID')}`;
        let totalHargaInput = document.getElementById('editTotalHarga');
        if (!totalHargaInput) {
            totalHargaInput = document.createElement('input');
            totalHargaInput.type = 'hidden';
            totalHargaInput.name = 'total_harga';
            totalHargaInput.id = 'editTotalHarga';
            document.getElementById('editForm').appendChild(totalHargaInput);
        }
        totalHargaInput.value = total;
    }

    function toggleBarangDropdown() {
        const dropdown = document.getElementById('barangDropdown');
        dropdown.classList.toggle('hidden');
        document.getElementById('filterBarang').focus();
    }

    function addItemRowToModal(item = {}, index = null) {
        const container = document.getElementById('modalItemsContainer');
        const itemIndex = index !== null ? index : container.children.length;
        const willBeOnlyItem = container.children.length === 0;
        const disableDelete = willBeOnlyItem;
        
        const barangList = @json($barangList);
        const namaBarang = barangList[item.nama] ? barangList[item.nama].nama_barang : (item.nama_barang || item.nama || '-');

        const itemRow = document.createElement('div');
        itemRow.className = 'grid grid-cols-12 gap-4 items-center item-row p-3 bg-gray-700 rounded-lg border border-gray-600';
        itemRow.innerHTML = `
            <div class="col-span-5 flex flex-col">
                <input type="text" value="${namaBarang}" class="w-full border border-gray-600 rounded-lg px-3 py-2.5 bg-gray-600 font-semibold text-base text-white" readonly>
                <input type="text" name="nama[]" value="${item.nama || item.kode || ''}" class="w-fit border border-gray-600 rounded px-2 py-1 mt-1 bg-gray-600 text-xs font-mono text-gray-300" readonly>
            </div>
            <div class="col-span-2">
                <input name="jumlah[]" type="number" value="${item.jumlah || ''}" class="w-full border border-gray-600 rounded-lg px-3 py-2.5 text-center focus:ring-2 focus:ring-gray-500 focus:border-gray-500 bg-gray-700 text-white" placeholder="Jumlah" min="1">
            </div>
            <div class="col-span-3">
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm">Rp</span>
                    <input name="harga[]" type="number" value="${item.harga || ''}" class="w-full border border-gray-600 rounded-lg pl-8 pr-3 py-2.5 text-right focus:ring-2 focus:ring-gray-500 focus:border-gray-500 bg-gray-700 text-white" placeholder="0" min="0">
                </div>
            </div>
            <div class="col-span-2 flex justify-center">
                <button type="button" onclick="removeItemFromModal(this)" class="text-red-500 hover:text-red-400 hover:bg-red-900 hover:bg-opacity-30 p-2 rounded-lg transition-colors ${disableDelete ? 'opacity-50 cursor-not-allowed' : ''}" title="Hapus Item" ${disableDelete ? 'disabled' : ''}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        `;
        container.appendChild(itemRow);

        const inputs = itemRow.querySelectorAll('input[name="jumlah[]"], input[name="harga[]"]');
        inputs.forEach(input => {
            input.addEventListener('input', updateModalTotalPrice);
        });

        if (container.children.length === 2) {
            const deleteButtons = container.querySelectorAll('button[onclick="removeItemFromModal(this)"]');
            deleteButtons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.classList.add('hover:text-red-400', 'hover:bg-red-900', 'hover:bg-opacity-30');
            });
        }
    }

    function addNewItemToModal() {
        const namaInput = document.getElementById('modalNewItemNama');
        const jumlahInput = document.getElementById('modalNewItemJumlah');
        const hargaInput = document.getElementById('modalNewItemHarga');
        const kodeBarang = namaInput.dataset.kode || '';

        if (!kodeBarang || !namaInput.value.trim() || !jumlahInput.value.trim() || !hargaInput.value.trim()) {
            Swal.fire({
                title: 'Data Tidak Lengkap',
                text: 'Mohon pilih barang dari daftar dan lengkapi semua field (jumlah dan harga).',
                icon: 'warning',
                background: '#1F2937',
                color: '#F9FAFB'
            });
            return;
        }

        let valid = false;
        document.querySelectorAll('.barang-item').forEach(item => {
            if (item.dataset.kode === kodeBarang && item.style.display !== 'none') valid = true;
        });
        if (!valid) {
            Swal.fire({
                title: 'Barang Tidak Valid',
                text: 'Silakan pilih barang dari daftar yang tersedia.',
                icon: 'warning',
                background: '#1F2937',
                color: '#F9FAFB'
            });
            namaInput.focus();
            return;
        }

        addItemRowToModal({
            nama_barang: namaInput.value.trim(),
            nama: kodeBarang,
            jumlah: jumlahInput.value.trim(),
            harga: hargaInput.value.trim()
        });
        clearNewItemFields();
        updateModalTotalPrice();
    }

    function clearNewItemFields() {
        const namaInput = document.getElementById('modalNewItemNama');
        const jumlahInput = document.getElementById('modalNewItemJumlah');
        const hargaInput = document.getElementById('modalNewItemHarga');
        namaInput.value = '';
        namaInput.dataset.kode = '';
        jumlahInput.value = '';
        hargaInput.value = '';
    }

    function removeItemFromModal(button) {
        const container = document.getElementById('modalItemsContainer');
        if (container.children.length > 1) {
            button.closest('.item-row').remove();
            if (container.children.length === 1) {
                const deleteButtons = container.querySelectorAll('button[onclick="removeItemFromModal(this)"]');
                deleteButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.classList.remove('hover:text-red-400', 'hover:bg-red-900', 'hover:bg-opacity-30');
                });
            }
            updateModalTotalPrice();
        } else {
            Swal.fire({
                title: 'Aksi Diblokir',
                text: 'Tidak dapat menghapus item terakhir. Sebuah struk harus memiliki minimal satu item.',
                icon: 'error',
                background: '#1F2937',
                color: '#F9FAFB'
            });
        }
    }

    function submitEditForm() {
        const form = document.getElementById('editForm');
        const container = document.getElementById('modalItemsContainer');

        if (container.children.length === 0) {
            Swal.fire({ title: 'Item Kosong', text: 'Minimal harus ada satu item.', icon: 'warning', background: '#1F2937', color: '#F9FAFB' });
            return;
        }

        let isValid = true;
        container.querySelectorAll('.item-row').forEach((row, index) => {
            const nama = row.querySelector('input[name="nama[]"]').value;
            const jumlah = row.querySelector('input[name="jumlah[]"]').value;
            const harga = row.querySelector('input[name="harga[]"]').value;

            if (!nama || !jumlah || !harga) {
                isValid = false;
            }
        });

        const namaToko = document.getElementById('editNamaToko').value;
        const nomorStruk = document.getElementById('editNomorStruk').value;
        const tanggalStruk = document.getElementById('editTanggalStruk').value;
        const status = document.getElementById('editStatus').value;

        if (!namaToko || !nomorStruk || !tanggalStruk || !status) {
            isValid = false;
        }

        if (!isValid) {
            Swal.fire({ title: 'Data Tidak Lengkap', text: 'Mohon lengkapi semua field yang diperlukan (termasuk semua field item).', icon: 'warning', background: '#1F2937', color: '#F9FAFB' });
            return;
        }

        form.submit();
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('deleteModal') && !document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
            if (document.getElementById('editModal') && !document.getElementById('editModal').classList.contains('hidden')) {
                closeEditModal();
            }
            if (document.getElementById('imageModal') && !document.getElementById('imageModal').classList.contains('hidden')) {
                closeModal();
            }
        }
    });

    function initAutocomplete(inputElement) {
        // ... (Fungsi ini tidak terpakai, bisa dihapus jika mau) ...
    }

    function initBarangDropdown() {
        const inputElement = document.getElementById('modalNewItemNama');
        const dropdown = document.getElementById('barangDropdown');
        const filterInput = document.getElementById('filterBarang');
        const barangItems = document.querySelectorAll('.barang-item');
        const hargaInput = document.getElementById('modalNewItemHarga');

        function updateAvailableBarangDropdown() {
            const selectedKodeList = Array.from(document.querySelectorAll('input[name="nama[]"]'))
                .map(input => input.value);

            barangItems.forEach(item => {
                const kode = item.dataset.kode;
                if (selectedKodeList.includes(kode)) {
                    item.style.display = 'none';
                } else {
                    item.style.display = 'flex';
                }
            });
        }

        if(inputElement) {
            inputElement.addEventListener('click', function(e) {
                e.preventDefault();
                if (dropdown) dropdown.classList.remove('hidden');
                updateAvailableBarangDropdown();
                if (filterInput) {
                    filterInput.value = ''; // Kosongkan filter setiap kali dibuka
                    filterInput.dispatchEvent(new Event('input')); // Tampilkan semua item
                    filterInput.focus();
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (dropdown && inputElement && !inputElement.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        if(filterInput) {
            filterInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                barangItems.forEach(item => {
                    const nama = item.dataset.nama.toLowerCase();
                    const kode = item.dataset.kode;
                    const selectedKodeList = Array.from(document.querySelectorAll('input[name="nama[]"]'))
                        .map(input => input.value);

                    if ((nama.includes(searchTerm) || kode.toLowerCase().includes(searchTerm)) && !selectedKodeList.includes(kode)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        barangItems.forEach(item => {
            item.addEventListener('click', function() {
                inputElement.value = this.dataset.nama;
                inputElement.dataset.kode = this.dataset.kode;
                if(hargaInput) hargaInput.value = this.dataset.harga || '';
                if(dropdown) dropdown.classList.add('hidden');
                document.getElementById('modalNewItemJumlah').focus();
            });
        });
    }
</script>

{{-- PERBAIKAN: CSS Kustom untuk modal dihapus --}}
<style>
    /* #editModal { ... } -- DIHAPUS */

    #barangDropdown {
        display: none;
    }
    #barangDropdown:not(.hidden) {
        display: block;
    }
    .barang-item {
        transition: background-color 0.2s;
    }
    .barang-item:hover {
        background-color: #4B5563; /* bg-gray-600 */
    }
    #filterBarang {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #374151; /* bg-gray-700 */
    }
    .toggle-label {
        width: 3rem;
        display: block;
    }
    .toggle-dot {
        transition: all 0.3s ease-in-out;
    }
    /* Style toggle diubah ke dark mode */
    body.pengeluaran-page .toggle-label {
        background-color: #2563eb; /* bg-blue-600 */
    }
    body.pengeluaran-page .toggle-dot {
        transform: translateX(1.5rem);
    }
    .toggle-label:hover {
        background-color: #4B5563; /* gray-600 hover */
    }
    body.pengeluaran-page .toggle-label:hover {
        background-color: #1D4ED8; /* blue-700 hover */
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slideIn {
        animation: slideIn 0.3s ease-out forwards;
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }
    .toggle-dot {
        transition: all 0.3s ease-in-out;
    }
    
    /* Autocomplete (tidak terpakai, tapi di-style dark) */
    .autocomplete-results {
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
    }
    .autocomplete-results div {
        padding: 10px 12px;
        cursor: pointer;
        border-bottom: 1px solid #374151; /* border-gray-700 */
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.2s;
    }
    .autocomplete-results div:hover {
        background-color: #4B5563; /* bg-gray-600 */
    }
    .autocomplete-results div.bg-gray-600 {
        background-color: #4B5563;
    }
    .autocomplete-results div span.text-xs {
        font-size: 0.75rem;
        color: #9CA3AF; /* text-gray-400 */
    }
    .autocomplete-results .text-gray-400 {
        padding: 12px;
        text-align: center;
    }
    * {
        transition: all 0.2s ease;
    }
    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }
    .loading-pulse {
        animation: pulse 1.5s infinite;
    }
    
    /* PERBAIKAN: 'white-space: nowrap' DIHAPUS dari 'th, td' */
    table { width: 100%; border-collapse: collapse; table-layout: auto; }
    th, td { 
        /* white-space: nowrap; -- DIHAPUS */
        overflow: hidden; 
        text-overflow: ellipsis; 
        padding: 12px 16px; 
        font-size: 0.875rem; 
        vertical-align: middle; 
    }
    thead th { 
        background-color: #1f2937; /* bg-gray-800 */
        padding-top: 10px; 
        padding-bottom: 10px; 
        color: #9ca3af; /* text-gray-400 */
    }
    /* PERBAIKAN: Aturan hover ini dihapus agar class Tailwind 'hover:' bisa berfungsi */
    /* tbody tr:hover { background-color: #1f2937; } */

    /* PERBAIKAN: Style scrollbar dark mode ditambahkan */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #1f2937; /* bg-gray-800 */
    }
    ::-webkit-scrollbar-thumb {
        background: #4B5563; /* bg-gray-600 */
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #6B7280; /* bg-gray-500 */
    }
</style>
@endsection