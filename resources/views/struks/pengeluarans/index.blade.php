@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .checkbox-style {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 16px;
        height: 16px;
        border: 2px solid #d1d5db;
        border-radius: 0.25rem;
        outline: none;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
    }

    .checkbox-style:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    .checkbox-style:checked::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 10px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .responsive-table {
        width: 100%;
        border-collapse: collapse;
    }

    .responsive-table th,
    .responsive-table td {
        padding: 8px 6px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .responsive-table th {
        background-color: #f9fafb;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.75rem;
        color: #6b7280;
    }

    .responsive-table td {
        font-size: 0.825rem;
        color: #374151;
    }

    @media (max-width: 640px) {
        .responsive-table {
            display: block;
            width: 100%;
            overflow-x: hidden;
        }

        .responsive-table thead {
            display: none;
        }

        .responsive-table tbody {
            display: block;
        }

        .responsive-table tr {
            display: block;
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background-color: #fff;
            padding: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .responsive-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: none;
            font-size: 0.825rem;
        }

        .responsive-table td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #4b5563;
            width: 40%;
            flex-shrink: 0;
        }

        .responsive-table td[data-label="Management SPK"],
        .responsive-table td[data-label="Pegawai"] {
            display: none;
        }

        .responsive-table td[data-label="Aksi"] {
            justify-content: center;
            gap: 6px;
        }

        .responsive-table td[data-label="Barang"],
        .responsive-table td[data-label="Jumlah"] {
            flex-direction: column;
            align-items: flex-start;
        }

        .responsive-table td[data-label="Barang"] div,
        .responsive-table td[data-label="Jumlah"] div {
            width: 100%;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out forwards;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .group:hover .group-hover\:block {
        display: block;
    }

    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }

    .group:hover .group-hover\:visible {
        visibility: visible;
    }

    .hover-scale {
        transition: transform 0.2s ease;
    }

    .hover-scale:hover {
        transform: scale(1.02);
    }

    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
    }

    .pagination li {
        margin: 0 3px;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 4px;
        text-decoration: none;
    }

    .pagination a {
        color: #4f46e5;
        border: 1px solid #e5e7eb;
    }

    .pagination a:hover {
        background-color: #f5f3ff;
    }

    .pagination .active span {
        background-color: #4f46e5;
        color: white;
    }

    .pagination .disabled span {
        color: #9ca3af;
        border-color: #e5e7eb;
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

    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

    .animate-marquee {
        animation: marquee 8s linear infinite;
    }

    .w-full {
        width: 100%;
    }

    .flex.justify-end {
        justify-content: flex-end;
    }

    .max-w-full {
        max-width: 100%;
    }

    @media (max-width: 640px) {
        .flex.justify-end {
            justify-content: flex-end;
            width: 100%;
        }
    }
</style>
@endpush

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-emerald-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 transform transition-all duration-300 animate-slideIn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Form untuk Bulk Delete -->
        <form id="bulkDeleteForm" method="POST" action="{{ route('pengeluarans.massDelete') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="selected_ids" id="selectedIds">
        </form>

        <!-- Header Section -->
        <div class="mb-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h1 class="text-xl font-semibold text-gray-800">Manajemen Struk</h1>
                    <p class="text-gray-600 text-sm">Kelola dan atur semua struk pemasukan</p>
                </div>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto items-center">
                    <div class="flex items-center gap-2">
                        <div class="relative inline-block w-10 align-middle select-none">
                            <input type="checkbox" id="toggle-pengeluaran" class="hidden" {{ request()->routeIs('struks.index') ? '' : 'checked' }}>
                            <label for="toggle-pengeluaran" title="Lihat Data Pemasukan" class="block h-5 rounded-full cursor-pointer transition-colors duration-300 ease-in-out {{ !request()->routeIs('struks.index') ? 'bg-indigo-400' : 'bg-gray-300' }}">
                                <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-md transform transition-transform duration-300 ease-in-out {{ !request()->routeIs('struks.index') ? 'translate-x-5' : '' }}"></span>
                            </label>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const toggle = document.getElementById('toggle-pengeluaran');
                            toggle.addEventListener('change', function() {
                                if (!this.checked) {
                                    window.location.href = "{{ route('struks.index') }}";
                                }
                            });
                        });
                    </script>
                    <div class="relative group">
                        <button class="flex items-center gap-1 px-3 py-1.5 bg-white text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Ekspor
                        </button>
                        <div class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 origin-top-right">
                            <a href="{{ route('struks.export.excel') }}" class="block px-3 py-1.5 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Format Excel</a>
                            <a href="{{ route('struks.export.csv') }}" class="block px-3 py-1.5 text-xs text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Format CSV</a>
                        </div>
                    </div>
                    <div id="bulkActionsContainer" class="hidden flex items-center gap-1 bg-red-50 rounded-lg p-1 border border-red-100">
                        <span id="selectedCount" class="px-1 py-0.5 bg-red-100 text-red-800 text-xs font-medium rounded-md">0 dipilih</span>
                        <button onclick="confirmBulkDelete()" class="flex items-center gap-1 px-2 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus
                        </button>
                        <button onclick="clearSelection()" class="p-0.5 text-red-400 hover:text-red-600 rounded-full hover:bg-red-100 transition-colors" title="Batal">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
            <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-indigo-100 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium text-gray-800">Data Struk Pemasukan</h3>
                            <p class="text-xs text-gray-500">{{ $pengeluarans->total() }} struk ditemukan</p>
                        </div>
                    </div>
                    <div class="relative w-full sm:w-48">
                        <input type="text" name="search" id="searchInput" class="pl-8 pr-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 w-full transition-all bg-transparent text-sm text-gray-800" value="{{ request('search') }}" autocomplete="off" placeholder="Cari berdasarkan Nama Toko">
                        <button type="button" class="absolute left-2 top-1.5 text-gray-400 pointer-events-none">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        @if (request('search'))
                        <button id="clearSearch" class="absolute right-2 top-1.5 text-gray-400 hover:text-gray-600" title="Bersihkan pencarian">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @endif
                        <div id="searchLoading" class="hidden absolute right-8 top-1.5">
                            <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-hidden px-2 py-4 flex flex-col items-start">
                @forelse ($pengeluarans as $index => $pengeluaran)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 my-4 p-4 flex flex-col gap-2 hover:shadow-md transition-all animate-fadeIn w-full">
                    <div class="flex justify-between items-start w-full">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="selected_ids[]" value="{{ $pengeluaran->id }}" class="checkbox-style rowCheckbox">
                            <span class="text-sm font-medium text-gray-700">{{ $pengeluarans->firstItem() + $index }}</span>
                        </div>
                        <div class="flex gap-2 justify-end">
                            <a href="{{ route('pengeluarans.show', $pengeluaran->id) }}" title="Lihat Detail">
                                <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('pengeluarans.edit', $pengeluaran->id) }}" title="Edit">
                                <svg class="w-4 h-4 text-indigo-600 hover:text-indigo-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button onclick="confirmSingleDelete('{{ route('pengeluarans.destroy', $pengeluaran->id) }}')" title="Hapus">
                                <svg class="w-4 h-4 text-gray-400 hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-gray-700 text-sm">
                        <div class="mb-1"><span class="font-medium">Nama Toko:</span> {{ $pengeluaran->nama_toko ?? '-' }}</div>
                        <div class="mb-1"><span class="font-medium">No. Struk:</span> <span class="text-gray-500">{{ $pengeluaran->nomor_struk ?? '-' }}</span></div>
                        <div class="mb-1"><span class="font-medium">Tanggal:</span> {{ $pengeluaran->tanggal->format('d M Y') }}</div>
                        <div class="mb-1"><span class="font-medium">Barang:</span>
                            @foreach ($pengeluaran->daftar_barang as $item)
                            <div class="flex items-center ml-2">
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-gray-400 mr-1 align-middle"></span>
                                <span>{{ $barangs[$item['nama']] ?? $item['nama'] }} (x{{ $item['jumlah'] }})</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="mb-1"><span class="font-medium">Total:</span> <span class="text-indigo-600 font-medium">Rp{{ number_format($pengeluaran->total, 0, ',', '.') }}</span></div>
                        <div class="mb-1 flex items-center gap-1">
                            <span class="font-medium">Status:</span>
                            <span class="px-1.5 py-0.5 text-xs font-medium rounded-full {{ $pengeluaran->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($pengeluaran->status) }}
                            </span>
                        </div>
                        <div class="mb-1"><span class="font-medium">Foto Struk:</span>
                            @if ($pengeluaran->foto_struk)
                                <a href="#" onclick="openModal('{{ asset('storage/'.$pengeluaran->foto_struk) }}')" class="text-indigo-600 hover:underline text-xs">Lihat Gambar</a>
                            @else
                                <span class="italic text-gray-400 text-xs">Tidak ada gambar</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-4 py-4 text-center text-gray-500 text-sm">Tidak ada pemasukan ditemukan.</div>
                @endforelse

                @if ($pengeluarans->hasPages())
                <div class="px-4 py-3 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <div class="text-xs text-gray-600">
                        Menampilkan {{ $pengeluarans->firstItem() }} sampai {{ $pengeluarans->lastItem() }} dari {{ $pengeluarans->total() }} hasil
                    </div>
                    <div class="flex space-x-1">
                        @if ($pengeluarans->onFirstPage())
                        <span class="px-2 py-1 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                        @else
                        <a href="{{ $pengeluarans->previousPageUrl() }}" class="px-2 py-1 rounded-lg border border-gray-200 text-indigo-600 hover:bg-indigo-50 transition-colors text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        @endif

                        @foreach ($pengeluarans->getUrlRange(1, $pengeluarans->lastPage()) as $page => $url)
                        @if ($page == $pengeluarans->currentPage())
                        <span class="px-2 py-1 rounded-lg bg-indigo-600 text-white text-xs">{{ $page }}</span>
                        @else
                        <a href="{{ $url }}" class="px-2 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-indigo-50 transition-colors text-xs">{{ $page }}</a>
                        @endif
                        @endforeach

                        @if ($pengeluarans->hasMorePages())
                        <a href="{{ $pengeluarans->nextPageUrl() }}" class="px-2 py-1 rounded-lg border border-gray-200 text-indigo-600 hover:bg-indigo-50 transition-colors text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        @else
                        <span class="px-2 py-1 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center">
    <div class="relative">
        <img id="modalImage" src="" alt="Preview Struk" class="max-h-[80vh] rounded shadow-lg select-none">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-white hover:text-gray-300 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-6 sm:align-middle sm:max-w-sm sm:w-full">
            <div class="bg-white px-4 pt-4 pb-3 sm:p-5 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 sm:mx-0 sm:h-8 sm:w-8">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-3 sm:text-left">
                        <h3 class="text-base leading-5 font-medium text-gray-900" id="modalTitle">Konfirmasi Penghapusan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus data yang dipilih?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitBulkDelete()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-3 py-1.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto">
                    Hapus
                </button>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-3 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Single Delete Confirmation Modal -->
<div id="singleDeleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-6 sm:align-middle sm:max-w-sm sm:w-full">
            <div class="bg-white px-4 pt-4 pb-3 sm:p-5 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 sm:mx-0 sm:h-8 sm:w-8">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-3 sm:text-left">
                        <h3 class="text-base leading-5 font-medium text-gray-900">Konfirmasi Penghapusan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus struk ini?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="singleDeleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-3 py-1.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeSingleDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-3 py-1.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
        const bulkActions = document.getElementById('bulkActionsContainer');
        const selectedCount = document.getElementById('selectedCount');
        const selectedIdsInput = document.getElementById('selectedIds');

        function updateBulkActions() {
            const selectedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);

            if (selectedCheckboxes.length > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = `${selectedCheckboxes.length} dipilih`;
                selectedIdsInput.value = JSON.stringify(selectedIds);
            } else {
                bulkActions.classList.add('hidden');
                selectedIdsInput.value = '';
            }

            if (selectAll) {
                const totalCheckboxes = rowCheckboxes.length;
                const checkedCount = selectedCheckboxes.length;

                selectAll.checked = checkedCount === totalCheckboxes && totalCheckboxes > 0;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCheckboxes;
            }
        }

        if (selectAll) {
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

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('rowCheckbox')) {
                updateBulkActions();
            }
        });

        window.clearSelection = function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAll) {
                selectAll.checked = false;
            }
            updateBulkActions();
        };

        window.confirmBulkDelete = function() {
            const selectedIds = JSON.parse(selectedIdsInput.value || '[]');
            if (selectedIds.length === 0) {
                alert('Pilih setidaknya satu data untuk dihapus.');
                return;
            }

            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        window.submitBulkDelete = function() {
            document.getElementById('bulkDeleteForm').submit();
        };

        window.closeDeleteModal = function() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        const searchInput = document.getElementById('searchInput');
        const clearSearchButton = document.getElementById('clearSearch');
        const searchLoading = document.getElementById('searchLoading');
        let searchTimeout;

        function loadData(searchTerm = '') {
            const url = new URL(window.location.href);
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }

            searchLoading.classList.remove('hidden');

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
                    if (newTableBody) {
                        document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
                    }

                    const newPagination = doc.querySelector('[class*="pagination"]');
                    if (newPagination) {
                        document.querySelector('[class*="pagination"]').parentNode.innerHTML = newPagination.parentNode.innerHTML;
                    }

                    const newResultsInfo = doc.querySelector('.text-xs.text-gray-600');
                    if (newResultsInfo) {
                        document.querySelector('.text-xs.text-gray-600').textContent = newResultsInfo.textContent;
                    }

                    searchLoading.classList.add('hidden');
                    animateRows();
                })
                .catch(error => {
                    console.error('Error:', error);
                    searchLoading.classList.add('hidden');
                });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);

                const cursorPosition = this.selectionStart;
                localStorage.setItem('searchCursorPosition', cursorPosition);

                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.trim();
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

            if (clearSearchButton) {
                clearSearchButton.addEventListener('click', function() {
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
                searchInput.value = searchTerm;
                loadData(searchTerm);
            });

            const savedCursorPosition = localStorage.getItem('searchCursorPosition');
            if (searchInput.value && savedCursorPosition) {
                setTimeout(() => {
                    searchInput.focus();
                    searchInput.selectionStart = searchInput.selectionEnd = parseInt(savedCursorPosition);
                }, 50);
            }
        }

        function animateRows() {
            document.querySelectorAll('tbody tr').forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(5px)';
                row.style.transition = 'all 0.3s ease-out';
                row.style.transitionDelay = `${index * 0.05}s`;

                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, 50);
            });
        }
    });

    function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
            closeModal();
        }
    });

    function confirmSingleDelete(deleteUrl) {
        const form = document.getElementById('singleDeleteForm');
        form.action = deleteUrl;
        document.getElementById('singleDeleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeSingleDeleteModal() {
        document.getElementById('singleDeleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('singleDeleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeSingleDeleteModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('singleDeleteModal').classList.contains('hidden')) {
            closeSingleDeleteModal();
        }
    });
</script>
@endsection