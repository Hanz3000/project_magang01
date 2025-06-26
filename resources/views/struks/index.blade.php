@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
        <div class="fixed top-4 right-4 z-50">
            <div
                class="bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2 transform transition-all duration-300 animate-slideIn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Struk</h1>
                <p class="text-gray-600">Kelola dan atur semua struk pembelian Anda</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <div class="relative group">
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Ekspor
                    </button>
                    <div
                        class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 origin-top-right">
                        <a href="{{ route('struks.export.excel') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Format
                            Excel</a>
                        <a href="{{ route('struks.export.csv') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Format
                            CSV</a>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Data Struk</h3>
                            <p class="text-sm text-gray-500">{{ $struks->total() }} struk ditemukan</p>
                        </div>
                    </div>
                    {{-- Search Form --}}
                    <form action="{{ route('struks.index') }}" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Cari struk..."
                            class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 w-64 transition-all"
                            value="{{ request('search') }}">
                        <button type="submit" class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        @if (request('search'))
                        <a href="{{ route('struks.index') }}"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600" title="Bersihkan pencarian">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Toko</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">No. Struk</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
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

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ $struks->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $struk->nama_toko }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $struk->nomor_struk }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-900">{{ date('d M Y', strtotime($struk->tanggal_struk)) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs space-y-1">
                                    @foreach ($items as $item)
                                    <div class="text-gray-700">{{ $item['nama'] ?? '-' }}</div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs space-y-1">
                                    @foreach ($items as $item)
                                    <div class="text-gray-500 text-sm">x{{ $item['jumlah'] ?? '-' }}</div>
                                    @endforeach
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-indigo-600">
                                Rp{{ number_format($totalHarga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($struk->foto_struk)
                                <button
                                    onclick="openModal('{{ asset('storage/struk_foto/' . $struk->foto_struk) }}')"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
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
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('struks.index', ['edit' => $struk->id, 'search' => request('search')]) }}"
                                        class="text-gray-400 hover:text-blue-600 p-1 rounded-full hover:bg-blue-50 transition-colors"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('struks.destroy', $struk->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus struk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-gray-400 hover:text-red-600 p-1 rounded-full hover:bg-red-50 transition-colors"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @if (request('edit') == $struk->id)
                        <tr class="bg-indigo-50">
                            <td colspan="9" class="px-6 py-4">
                                <form method="POST" action="{{ route('struks.updateItems', $struk->id) }}"
                                    class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <h4 class="font-medium text-gray-700 mb-2">Edit Item Struk</h4>

                                    <div id="itemsContainer" class="space-y-3">
                                        {{-- Existing items will be rendered here by PHP --}}
                                        @foreach ($items as $idx => $item)
                                        <div class="flex items-center space-x-4 item-row">
                                            {{-- Hidden input to identify the original item index --}}
                                            <input type="hidden" name="item_index[]" value="{{ $idx }}">
                                            <input name="nama[]" value="{{ $item['nama'] ?? '' }}"
                                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                                                placeholder="Nama item">
                                            <input name="jumlah[]" type="number"
                                                value="{{ $item['jumlah'] ?? '' }}"
                                                class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                                                placeholder="Jumlah">
                                            <input name="harga[]" type="number"
                                                value="{{ $item['harga'] ?? '' }}"
                                                class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-right focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                                                placeholder="Harga">
                                            @if (count($items) > 1)
                                            <button type="button"
                                                onclick="confirmDeleteItem('{{ $struk->id }}', '{{ $idx }}')"
                                                class="text-red-500 hover:text-red-700 p-1" title="Hapus">
                                                <svg class="w-5 h-5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                            @else
                                            {{-- Placeholder for alignment if only one item --}}
                                            <span class="w-5 h-5"></span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>

                                    {{-- Row untuk tambah item baru --}}
                                    <div
                                        class="flex items-center space-x-4 item-row border-2 border-dashed border-indigo-200 rounded-lg p-3 bg-indigo-25">
                                        <input type="text"
                                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                                            placeholder="Nama item baru" id="newItemNama">
                                        <input type="number"
                                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                                            placeholder="Jumlah" id="newItemJumlah">
                                        <input type="number"
                                            class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-right focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400"
                                            placeholder="Harga" id="newItemHarga">
                                        <button type="button" onclick="addNewItemField()"
                                            class="px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Tambah Item
                                        </button>
                                    </div>

                                    <div class="flex justify-end space-x-3 pt-2">
                                        <a href="{{ route('struks.index', ['search' => request('search')]) }}"
                                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
                                        <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Simpan
                                            Perubahan</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">Tidak ada struk ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($struks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $struks->appends(['search' => request('search')])->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75" onclick="closeModal()"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <img id="modalImage" src="" alt="Struk"
                    class="w-full h-auto max-h-[80vh] object-contain">
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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

    // Close modal when clicking outside image
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Confirm item deletion
    function confirmDeleteItem(strukId, index) {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/struks/${strukId}/item/${index}`; // Corrected template literal syntax

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

        // Only add if all new item fields have values
        if (newItemNama.value.trim() && newItemJumlah.value.trim() && newItemHarga.value.trim()) {
            const newItemRow = document.createElement('div');
            newItemRow.classList.add('flex', 'items-center', 'space-x-4', 'item-row');

            // IMPORTANT: These new inputs use the same 'name' attributes as existing items for submission
            // This is crucial for the simplified controller logic.
            newItemRow.innerHTML = `
                <input name="nama[]" value="${newItemNama.value}" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" placeholder="Nama item baru">
                <input name="jumlah[]" type="number" value="${newItemJumlah.value}" class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" placeholder="Jumlah">
                <input name="harga[]" type="number" value="${newItemHarga.value}" class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-right focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400" placeholder="Harga">
                <button type="button" onclick="this.closest('.item-row').remove()" class="text-red-500 hover:text-red-700 p-1" title="Hapus">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            itemsContainer.appendChild(newItemRow);

            // Clear the input fields after adding
            newItemNama.value = '';
            newItemJumlah.value = '';
            newItemHarga.value = '';
        } else {
            alert('Harap isi semua kolom untuk item baru (Nama, Jumlah, Harga).');
        }
    }

    // Auto-hide success message after 5 seconds
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
</script>

<style>
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

    .group:hover .group-hover\:block {
        display: block;
    }

    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }

    .group:hover .group-hover\:visible {
        visibility: visible;
    }

    .animate-slideIn {
        animation: slideIn 0.3s ease-out forwards;
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
</style>
@endsection