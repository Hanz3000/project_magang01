@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
        <div class="fixed top-4 right-4 z-50">
            <div class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 transform transition-all duration-300 animate-slideIn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <!-- Bulk Delete Form -->
        <form id="bulkDeleteForm" method="POST" action="{{ route('struks.bulk-delete') }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="selected_ids" id="selectedIds">
        </form>

        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <!-- Title Section -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Manajemen Struk</h1>
                    <p class="text-gray-600">Kelola dan atur semua struk pemasukan</p>
                </div>
                <!-- Action Buttons & Toggle Switch -->
                <div class="flex flex-wrap gap-2 w-full sm:w-auto items-center">
                    <!-- Toggle Switch -->
                    @php
                    $isPemasukan = request()->routeIs('struks.index');
                    @endphp

                    <div class="flex items-center gap-2 mt-2 sm:mt-0">
                        <div class="relative inline-block w-12 align-middle select-none">
                            <input type="checkbox" id="toggle-struk" class="hidden" {{ $isPemasukan ? '' : 'checked' }}>
                            <label for="toggle-struk"
                                title="Lihat Data Pengeluaran"
                                class="block h-6 rounded-full cursor-pointer transition-colors duration-300 ease-in-out
    {{ $isPemasukan ? 'bg-indigo-400' : 'bg-gray-300' }}">

                                <span
                                    class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transform transition-transform duration-300 ease-in-out
                {{ $isPemasukan ? '' : 'translate-x-6' }}">
                                </span>
                            </label>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const toggle = document.getElementById('toggle-struk');
                            toggle.addEventListener('change', function() {
                                if (this.checked) {
                                    window.location.href = "{{ route('pengeluarans.index') }}";
                                }
                            });
                        });
                    </script>

                    <!-- Export Button -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Ekspor
                        </button>
                        <div class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 origin-top-right">
                            <a href="{{ route('struks.export.excel') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Format Excel</a>
                            <a href="{{ route('struks.export.csv') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Format CSV</a>
                        </div>
                    </div>
                    <!-- Bulk Actions ... (lanjutkan seperti semula) -->
                    <div id="bulkActionsContainer" class="hidden flex items-center gap-2 bg-red-50 rounded-lg p-1 border border-red-100">
                        <span id="selectedCount" class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-md">0 dipilih</span>
                        <button onclick="confirmBulkDelete()" class="flex items-center gap-2 px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus
                        </button>
                        <button onclick="clearSelection()" class="p-1 text-red-400 hover:text-red-600 rounded-full hover:bg-red-100 transition-colors" title="Batal">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Data Struk</h3>
                            <p class="text-sm text-gray-500">{{ $struks->total() }} struk ditemukan</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="text" name="search" id="searchInput" placeholder="Cari struk..."
                            class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 w-64 transition-all"
                            value="{{ request('search') }}" autocomplete="off">
                        <button type="button" class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        @if (request('search'))
                        <button id="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600" title="Bersihkan pencarian">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Toko</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">No. Struk</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Masuk</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Keluar</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Struk</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($struks as $index => $struk)
                        @php
                        $items = json_decode($struk->items, true);
                        $totalHarga = collect($items)->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
                        @endphp

                        <tr class="hover:bg-gray-50 transition-colors animate-fadeIn">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="checkbox" name="selected_ids[]" value="{{ $struk->id }}"
                                    class="row-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $struk->nama_toko }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $struk->nomor_struk }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900">{{ date('d M Y', strtotime($struk->tanggal_struk)) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900">
                                    @if($struk->tanggal_keluar)
                                    {{ date('d M Y', strtotime($struk->tanggal_keluar)) }}
                                    @else
                                    <span class="text-gray-400 italic text-xs">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs space-y-2">
                                    @foreach ($items as $item)
                                    <div class="flex items-start">
                                        <span class="inline-block w-2 h-2 rounded-full bg-gray-400 mt-2 mr-2 flex-shrink-0"></span>
                                        <span class="text-gray-700 break-words">{{ $item['nama'] ?? '-' }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs space-y-2">
                                    @foreach ($items as $item)
                                    <div class="text-gray-500 text-sm">
                                        <span class="whitespace-nowrap">x{{ $item['jumlah'] ?? '-' }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-indigo-600">
                                Rp{{ number_format($totalHarga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($struk->foto_struk)
                                <button onclick="openModal('{{ asset('storage/struk_foto/' . $struk->foto_struk) }}')"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-xs text-gray-500">Lihat</span>
                                </button>
                                @else
                                <span class="text-gray-400 italic text-xs">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('struks.show', $struk->id) }}"
                                        class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors"
                                        title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button type="button" onclick="openEditModal({{ $struk->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 px-3 py-1 rounded-md transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('struks.destroy', $struk->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus struk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('struks.destroy', $struk->id) }}')"
                                            class="text-gray-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition-colors"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @if (request('edit') == $struk->id)
                        <tr class="bg-indigo-50 animate-fadeIn">
                            <td colspan="10" class="px-0 py-4">
                                <div class="px-6 max-w-5xl ml-auto">
                                    <form method="POST" action="{{ route('struks.updateItems', $struk->id) }}" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <h4 class="font-medium text-gray-700 mb-2">Edit Item Struk</h4>

                                        <div id="itemsContainer" class="space-y-3">
                                            @foreach ($items as $idx => $item)
                                            <div class="flex items-center space-x-4 item-row">
                                                <input type="hidden" name="item_index[]" value="{{ $idx }}">
                                                <div class="flex-1 relative">
                                                    <select name="nama[]"
                                                        class="item-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400">
                                                        <option value="" disabled {{ empty($item['nama']) ? 'selected' : '' }}>Pilih Barang</option>
                                                        @foreach ($barangList as $barang)
                                                        <option value="{{ $barang->nama_barang }}"
                                                            {{ (isset($item['nama']) && $item['nama'] == $barang->nama_barang) ? 'selected' : '' }}>
                                                            {{ $barang->nama_barang }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input name="jumlah[]" type="number" value="{{ $item['jumlah'] ?? '' }}"
                                                    class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                                                    placeholder="Jumlah">
                                                <input name="harga[]" type="number" value="{{ $item['harga'] ?? '' }}"
                                                    class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-right focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                                                    placeholder="Harga">
                                                @if (count($items) > 1)
                                                <button type="button"
                                                    onclick="confirmDeleteItem('{{ $struk->id }}', '{{ $idx }}')"
                                                    class="text-red-500 hover:text-red-700 p-1" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                @else
                                                <span class="w-5 h-5"></span>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>

                                        <div class="flex items-center space-x-4 item-row border-2 border-dashed border-indigo-200 rounded-lg p-3 bg-indigo-25">
                                            <select class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" id="newItemNama" style="min-width:0">
                                                <option value="" disabled selected>Pilih Barang</option>
                                                @foreach ($barangList as $barang)
                                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                            <input type="number" class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" placeholder="Jumlah" id="newItemJumlah">
                                            <input type="number" class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-right focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" placeholder="Harga" id="newItemHarga">
                                            <button type="button" onclick="addNewItemField()" class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Tambah Item
                                            </button>
                                        </div>

                                        <div class="flex justify-end space-x-3 pt-2">
                                            <a href="{{ route('struks.index', ['search' => request('search')]) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
                                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr class="animate-fadeIn">
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500">Tidak ada struk ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($struks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $struks->firstItem() }} sampai {{ $struks->lastItem() }} dari {{ $struks->total() }} hasil
                </div>
                <div class="flex space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($struks->onFirstPage())
                    <span class="px-3 py-1 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                    @else
                    <a href="{{ $struks->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-200 text-indigo-600 hover:bg-indigo-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($struks->getUrlRange(1, $struks->lastPage()) as $page => $url)
                    @if ($page == $struks->currentPage())
                    <span class="px-3 py-1 rounded-lg bg-indigo-600 text-white">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-indigo-50 transition-colors">{{ $page }}</a>
                    @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($struks->hasMorePages())
                    <a href="{{ $struks->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-200 text-indigo-600 hover:bg-indigo-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    @else
                    <span class="px-3 py-1 rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center">
    <div class="relative">
        <img id="modalImage" src="" class="max-h-[80vh] rounded shadow-lg select-none">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-white hover:text-gray-300 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Edit Item Struk</h3>
                        <p class="text-sm text-gray-500">Ubah, tambah, atau hapus item dalam struk</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-4">
            <form method="POST" action="" id="editItemsForm">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-12 gap-4 pb-3 mb-4 border-b border-gray-200">
                    <div class="col-span-5">
                        <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 text-center">Jumlah</label>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-sm font-medium text-gray-700 text-right">Harga Satuan</label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 text-center">Aksi</label>
                    </div>
                </div>
                <div id="modalItemsContainer" class="space-y-3 mb-6"></div>
                <div class="border-2 border-dashed border-indigo-300 rounded-lg p-4 bg-indigo-50">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-sm font-medium text-indigo-700">Tambah Item Baru</span>
                    </div>
                    <div class="grid grid-cols-12 gap-4 items-end">
                        <div class="col-span-5">
                            <select class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 bg-white" id="modalNewItemNama">
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach ($barangList as $barang)
                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="number" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-center focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" placeholder="Jumlah" id="modalNewItemJumlah" min="1">
                        </div>
                        <div class="col-span-3">
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                                <input type="number" class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2.5 text-right focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" placeholder="0" id="modalNewItemHarga" min="0">
                            </div>
                        </div>
                        <div class="col-span-2 flex justify-center">
                            <button type="button" onclick="addNewItemToModal()" class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium flex items-center gap-2 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button type="button" onclick="submitEditForm()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Konfirmasi Penghapusan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus struk ini?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize bulk actions
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulkActionsContainer');
        const selectedCount = document.getElementById('selectedCount');
        const selectedIdsInput = document.getElementById('selectedIds');

        function updateBulkActions() {
            const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);

            if (selectedCheckboxes.length > 0) {
                bulkActions.classList.remove('hidden');
                selectedCount.textContent = `${selectedCheckboxes.length} dipilih`;
                selectedIdsInput.value = selectedIds.join(',');

                if (selectAll) {
                    selectAll.checked = selectedCheckboxes.length === rowCheckboxes.length;
                    selectAll.indeterminate = selectedCheckboxes.length > 0 && selectedCheckboxes.length < rowCheckboxes.length;
                }
            } else {
                bulkActions.classList.add('hidden');
                selectedIdsInput.value = '';
                if (selectAll) selectAll.checked = false;
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
            if (e.target.classList.contains('row-checkbox')) {
                updateBulkActions();
            }
        });

        // Toggle switch functionality
        if (window.location.pathname.includes('pengeluarans')) {
            document.body.classList.add('pengeluaran-page');
        }

        document.querySelectorAll('.toggle-label').forEach(label => {
            label.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = this.parentElement.href;
            });
        });

        // Auto-hide success message
        @if(session('success'))
        setTimeout(() => {
            const successMessage = document.querySelector('.fixed.top-4.right-4');
            if (successMessage) {
                successMessage.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    successMessage.remove();
                }, 300);
            }
        }, 5000);
        @endif

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const cursorPosition = this.selectionStart;
            localStorage.setItem('searchCursorPosition', cursorPosition);

            searchTimeout = setTimeout(() => {
                const searchTerm = searchInput.value.trim();
                const url = new URL(window.location.href);

                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }

                @if(request('edit'))
                url.searchParams.set('edit', '{{ request('
                    edit ') }}');
                @endif

                window.location.href = url.toString();
            }, 500);
        });

        if (clearSearch) {
            clearSearch.addEventListener('click', function() {
                localStorage.removeItem('searchCursorPosition');
                const url = new URL(window.location.href);
                url.searchParams.delete('search');

                @if(request('edit'))
                url.searchParams.set('edit', '{{ request('
                    edit ') }}');
                @endif

                window.location.href = url.toString();
            });
        }

        // Restore cursor position
        const savedCursorPosition = localStorage.getItem('searchCursorPosition');
        if (searchInput.value && savedCursorPosition) {
            setTimeout(() => {
                searchInput.focus();
                searchInput.selectionStart = searchInput.selectionEnd = parseInt(savedCursorPosition);
                localStorage.removeItem('searchCursorPosition');
            }, 50);
        }

        // Table row animations
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
    });

    // Clear selection
    function clearSelection() {
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        document.getElementById('bulkActionsContainer').classList.add('hidden');
        document.getElementById('selectedIds').value = '';
    }

    // Confirm bulk delete
    function confirmBulkDelete() {
        const selectedIds = document.getElementById('selectedIds').value;
        if (!selectedIds) {
            alert('Pilih setidaknya satu struk untuk dihapus');
            return;
        }

        const count = selectedIds.split(',').length;
        if (confirm(`Apakah Anda yakin ingin menghapus ${count} struk yang dipilih?`)) {
            document.getElementById('bulkDeleteForm').submit();
        }
    }

    // Image modal functions
    function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Delete modal functions
    function openDeleteModal(formAction) {
        document.getElementById('deleteForm').action = formAction;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Edit modal functions
    let currentEditStrukId = null;

    function openEditModal(strukId) {
        currentEditStrukId = strukId;
        document.getElementById('editModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('editItemsForm').action = `/struks/${strukId}/update-items`;
        loadExistingItems(strukId);
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('modalItemsContainer').innerHTML = '';
        clearNewItemFields();
    }

    function loadExistingItems(strukId) {
        const container = document.getElementById('modalItemsContainer');
        fetch(`/struks/${strukId}/items`)
            .then(response => response.json())
            .then(data => {
                const items = data.items;
                container.innerHTML = '';
                items.forEach((item, index) => {
                    addItemRowToModal(item, index);
                });

                if (items.length === 1) {
                    const deleteButton = container.querySelector('button[onclick="removeItemFromModal(this)"]');
                    if (deleteButton) {
                        deleteButton.disabled = true;
                        deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
                        deleteButton.classList.remove('hover:text-red-700', 'hover:bg-red-50');
                    }
                }
            })
            .catch(error => {
                container.innerHTML = '<div class="text-red-500">Gagal memuat data item.</div>';
            });
    }

    function addItemRowToModal(item = {}, index = null) {
        const container = document.getElementById('modalItemsContainer');
        const itemIndex = index !== null ? index : container.children.length;

        let options = `<option value="" disabled ${!item.nama ? 'selected' : ''}>Pilih Barang</option>`;
        @foreach($barangList as $barang)
        options += `<option value="{{ $barang->nama_barang }}" ${item.nama === '{{ $barang->nama_barang }}' ? 'selected' : ''}>{{ $barang->nama_barang }}</option>`;
        @endforeach

        const willBeOnlyItem = container.children.length === 0;
        const disableDelete = willBeOnlyItem;

        const itemRow = document.createElement('div');
        itemRow.className = 'grid grid-cols-12 gap-4 items-center item-row p-3 bg-gray-50 rounded-lg border border-gray-200';
        itemRow.innerHTML = `
            <input type="hidden" name="item_index[]" value="${itemIndex}">
            <div class="col-span-5">
                <select name="nama[]" class="item-select w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 bg-white">
                    ${options}
                </select>
            </div>
            <div class="col-span-2">
                <input name="jumlah[]" type="number" value="${item.jumlah || ''}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-center focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                    placeholder="Jumlah" min="1">
            </div>
            <div class="col-span-3">
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp</span>
                    <input name="harga[]" type="number" value="${item.harga || ''}"
                        class="w-full border border-gray-300 rounded-lg pl-8 pr-3 py-2.5 text-right focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                        placeholder="0" min="0">
                </div>
            </div>
            <div class="col-span-2 flex justify-center">
                <button type="button" onclick="removeItemFromModal(this)"
                    class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors ${disableDelete ? 'opacity-50 cursor-not-allowed' : ''}"
                    title="Hapus Item" ${disableDelete ? 'disabled' : ''}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        `;
        container.appendChild(itemRow);

        if (container.children.length === 2) {
            const deleteButtons = container.querySelectorAll('button[onclick="removeItemFromModal(this)"]');
            deleteButtons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.classList.add('hover:text-red-700', 'hover:bg-red-50');
            });
        }
    }

    function addNewItemToModal() {
        const nama = document.getElementById('modalNewItemNama').value;
        const jumlah = document.getElementById('modalNewItemJumlah').value;
        const harga = document.getElementById('modalNewItemHarga').value;

        if (!nama || !jumlah || !harga) {
            alert('Mohon lengkapi semua field untuk item baru');
            return;
        }

        const newItem = {
            nama,
            jumlah,
            harga
        };
        addItemRowToModal(newItem);
        clearNewItemFields();
    }

    function clearNewItemFields() {
        document.getElementById('modalNewItemNama').value = '';
        document.getElementById('modalNewItemJumlah').value = '';
        document.getElementById('modalNewItemHarga').value = '';
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
                    btn.classList.remove('hover:text-red-700', 'hover:bg-red-50');
                });
            }
        } else {
            alert('Tidak dapat menghapus item terakhir');
        }
    }

    function submitEditForm() {
        const form = document.getElementById('editItemsForm');
        const container = document.getElementById('modalItemsContainer');

        if (container.children.length === 0) {
            alert('Minimal harus ada satu item');
            return;
        }

        let isValid = true;
        container.querySelectorAll('.item-row').forEach((row, index) => {
            const nama = row.querySelector('select[name="nama[]"]').value;
            const jumlah = row.querySelector('input[name="jumlah[]"]').value;
            const harga = row.querySelector('input[name="harga[]"]').value;

            if (!nama || !jumlah || !harga) {
                isValid = false;
            }
        });

        if (!isValid) {
            alert('Mohon lengkapi semua field yang diperlukan');
            return;
        }

        form.submit();
    }

    // Close modals when clicking outside
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

    // Close modals with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
            if (!document.getElementById('editModal').classList.contains('hidden')) {
                closeEditModal();
            }
            if (!document.getElementById('imageModal').classList.contains('hidden')) {
                closeModal();
            }
        }
    });

    // Confirm item deletion
    function confirmDeleteItem(strukId, index) {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/struks/${strukId}/item/${index}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Function to add new item fields
    function addNewItemField() {
        const newItemNama = document.getElementById('newItemNama');
        const newItemJumlah = document.getElementById('newItemJumlah');
        const newItemHarga = document.getElementById('newItemHarga');
        const itemsContainer = document.getElementById('itemsContainer');

        if (newItemNama.value && newItemJumlah.value.trim() && newItemHarga.value.trim()) {
            const newItemRow = document.createElement('div');
            newItemRow.classList.add('flex', 'items-center', 'space-x-4', 'item-row');

            newItemRow.innerHTML = `
            <div class="flex-1 relative">
                <input name="nama[]" value="${newItemNama.value}" 
                    class="item-search w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" 
                    placeholder="Nama item">
                <div class="autocomplete-results absolute z-10 w-full mt-1 hidden bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"></div>
            </div>
            <input name="jumlah[]" type="number" value="${newItemJumlah.value}" 
                class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" 
                placeholder="Jumlah">
            <input name="harga[]" type="number" value="${newItemHarga.value}" 
                class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-right focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" 
                placeholder="Harga">
            <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-500 hover:text-red-700 p-1" title="Hapus">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
            itemsContainer.appendChild(newItemRow);

            const newInput = newItemRow.querySelector('.item-search');
            initAutocomplete(newInput);

            newItemNama.selectedIndex = 0;
            newItemJumlah.value = '';
            newItemHarga.value = '';
        } else {
            alert('Harap isi semua kolom untuk item baru (Nama, Jumlah, Harga).');
        }
    }

    // Initialize autocomplete for item search
    function initAutocomplete(inputElement) {
        const resultsContainer = inputElement.nextElementSibling;

        inputElement.addEventListener('input', function(e) {
            const searchTerm = e.target.value.trim();

            if (searchTerm.length < 2) {
                resultsContainer.classList.add('hidden');
                return;
            }

            fetch(`/struks/search-items?term=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';

                    if (data.length === 0) {
                        resultsContainer.classList.add('hidden');
                        return;
                    }

                    data.forEach(item => {
                        const option = document.createElement('div');
                        option.className = 'px-4 py-2 hover:bg-indigo-50 cursor-pointer';
                        option.textContent = item.nama;
                        option.addEventListener('click', function() {
                            inputElement.value = item.nama;
                            const priceInput = inputElement.closest('.item-row').querySelector('input[name*="harga"]');
                            if (priceInput && (!priceInput.value || priceInput.value === '0')) {
                                priceInput.value = item.harga;
                            }
                            resultsContainer.classList.add('hidden');
                        });
                        resultsContainer.appendChild(option);
                    });

                    resultsContainer.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching autocomplete data:', error);
                    resultsContainer.classList.add('hidden');
                });
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                resultsContainer.classList.add('hidden');
            }
        });
    }
</script>

<style>
    /* Toggle Switch Styles */
    .toggle-label {
        width: 3rem;
        display: block;
    }

    .toggle-dot {
        transition: all 0.3s ease-in-out;
    }

    /* Active state when on pengeluaran page */
    body.pengeluaran-page .toggle-label {
        background-color: #4f46e5;
    }

    body.pengeluaran-page .toggle-dot {
        transform: translateX(1.5rem);
    }

    /* Hover effects */
    .toggle-label:hover {
        background-color: #a5b4fc;
    }

    body.pengeluaran-page .toggle-label:hover {
        background-color: #4338ca;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out forwards;
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
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

    /* Custom pagination styling */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
    }

    .pagination li {
        margin: 0 4px;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 6px;
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

    .autocomplete-results {
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .autocomplete-results div {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
    }

    .autocomplete-results div:last-child {
        border-bottom: none;
    }

    .autocomplete-results div:hover {
        background-color: #f3f4f6;
    }

    * {
        transition: all 0.2s ease;
    }

    /* Loading animation */
    @keyframes pulse {
        0% {
            opacity: 0.6;
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0.6;
        }
    }

    .loading-pulse {
        animation: pulse 1.5s infinite;
    }
</style>
@endsection