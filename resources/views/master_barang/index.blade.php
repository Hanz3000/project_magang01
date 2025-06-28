@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Master Barang</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola daftar barang yang tersedia</p>
            </div>
            <a href="{{ route('master-barang.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Barang
            </a>
        </div>

        @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mx-6 mt-4 rounded">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
        @endif

        <div class="px-6 py-4">
            <!-- Kontrol untuk bulk actions - hanya muncul jika ada item terpilih -->
            <div id="bulk-actions" class="flex items-center justify-between mb-4 p-3 bg-red-50 border border-red-200 rounded-lg hidden">
                <span id="selected-count" class="text-sm text-red-700 font-medium">
                    <span id="count">0</span> item dipilih
                </span>
                <form action="{{ route('master-barang.bulk-delete') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua barang yang dipilih?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="bulk-ids" name="ids" value="">
                    <button type="submit" id="bulk-delete-btn"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Terpilih
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="select-all" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span>NO</span>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NAMA BARANG</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($barangs as $barang)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" name="ids[]" value="{{ $barang->id }}"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded row-checkbox">
                                    <span>{{ $loop->iteration }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('master-barang.edit', $barang->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded-full transition-colors duration-150"
                                        title="Edit barang">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('master-barang.destroy', $barang->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus barang ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-full transition-colors duration-150"
                                            title="Hapus barang">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-gray-600 font-medium">Belum ada data barang</p>
                                    <p class="text-gray-400 text-xs mt-1">Klik "Tambah Barang" untuk menambahkan data pertama</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const countSpan = document.getElementById('count');
        const bulkIdsInput = document.getElementById('bulk-ids');

        // Function to update UI based on selected checkboxes
        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const hasSelected = checkedBoxes.length > 0;

            // Show/hide bulk actions bar
            if (hasSelected) {
                bulkActions.classList.remove('hidden');
                countSpan.textContent = checkedBoxes.length;
                // Update hidden input with selected IDs
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
                bulkIdsInput.value = selectedIds.join(',');
            } else {
                bulkActions.classList.add('hidden');
            }

            // Update select all checkbox state
            if (checkedBoxes.length === rowCheckboxes.length && rowCheckboxes.length > 0) {
                selectAll.checked = true;
                selectAll.indeterminate = false;
            } else if (checkedBoxes.length > 0) {
                selectAll.checked = false;
                selectAll.indeterminate = true;
            } else {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
        }

        // Select all functionality
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        // Individual checkbox functionality
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        // Initial state
        updateBulkActions();
    });
</script>
@endsection