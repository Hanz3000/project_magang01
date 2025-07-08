@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20">

        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-6 text-white">
            <h2 class="text-xl font-bold">Edit Item Struk</h2>
            <p class="text-sm opacity-80">Ubah, tambah, atau hapus item dalam struk</p>
        </div>

        <form action="{{ route('pengeluarans.update', $pengeluaran->id) }}" method="POST" class="p-6 space-y-4" id="pengeluaran-form">
            @csrf
            @method('PUT')

            {{-- Hidden fields for main data --}}
            <input type="hidden" name="nama_toko" value="{{ $pengeluaran->nama_toko }}">
            <input type="hidden" name="nomor_struk" value="{{ $pengeluaran->nomor_struk }}">
            <input type="hidden" name="tanggal" value="{{ $pengeluaran->tanggal }}">
            <input type="hidden" name="pegawai_id" value="{{ $pengeluaran->pegawai_id }}">

            {{-- Existing Items --}}
            <div class="space-y-3">
                <div class="grid grid-cols-4 gap-4 font-medium text-gray-700 pb-2 border-b">
                    <div>Nama Barang</div>
                    <div>Jumlah</div>
                    <div>Harga Satuan</div>
                    <div>Aksi</div>
                </div>

                @foreach ($pengeluaran->daftar_barang as $index => $item)
                <div class="grid grid-cols-4 gap-4 items-center py-3 border-b existing-item">
                    <div class="font-medium">{{ $item['nama'] }} ({{ $item['kode_barang'] ?? '' }})</div>
                    <div>
                        <input type="number" name="existing_items[{{ $index }}][jumlah]" value="{{ $item['jumlah'] }}" min="1"
                            class="w-full border rounded px-3 py-2 existing-jumlah-input">
                    </div>
                    <div>
                        <input type="number" name="existing_items[{{ $index }}][harga]" value="{{ $item['harga'] }}" min="0"
                            class="w-full border rounded px-3 py-2 existing-harga-input">
                    </div>
                    <div>
                        <input type="hidden" name="existing_items[{{ $index }}][nama]" value="{{ $item['nama'] }}">
                        <input type="hidden" name="existing_items[{{ $index }}][kode_barang]" value="{{ $item['kode_barang'] ?? '' }}">
                        <button type="button" class="text-red-500 hover:text-red-700 focus:outline-none hapus-item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Add New Item Section --}}
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-medium">+ Tambah Item Baru</h3>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-3">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Cari barang atau pilih dari daftar</label>
                        <select class="w-full border rounded px-3 py-2 searchable-select barang-select">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                            <option value="{{ $barang->nama_barang }}"
                                    data-kode="{{ $barang->kode_barang }}"
                                    data-harga="{{ $barang->harga_satuan }}">
                                {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Jumlah</label>
                        <input type="number" class="w-full border rounded px-3 py-2 new-jumlah-input" value="1" min="1">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Harga Satuan</label>
                        <input type="number" class="w-full border rounded px-3 py-2 new-harga-input" min="0">
                    </div>
                </div>

                <button type="button" id="tambah-barang" 
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    + Tambah
                </button>
            </div>

            {{-- Total Section --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-lg">Total Semua Item</span>
                    <span id="total-semua" class="font-bold text-xl">Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}</span>
                </div>
                <input type="hidden" name="total" id="total-hidden" value="{{ $pengeluaran->total }}">
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('pengeluarans.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" id="submit-button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Template for new item (hidden) -->
<template id="template-barang-baru">
    <div class="grid grid-cols-4 gap-4 items-center py-3 border-b barang-baru">
        <div class="new-nama-barang"></div>
        <div>
            <input type="number" name="new_items[][jumlah]" class="w-full border rounded px-3 py-2 new-jumlah-field" min="1">
        </div>
        <div>
            <input type="number" name="new_items[][harga]" class="w-full border rounded px-3 py-2 new-harga-field" min="0">
        </div>
        <div>
            <input type="hidden" name="new_items[][nama]" class="new-nama-field">
            <input type="hidden" name="new_items[][kode_barang]" class="new-kode-field">
            <button type="button" class="hapus-barang text-red-500 hover:text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Calculate total
    function calculateTotal() {
        let total = 0;

        // Calculate from existing items
        document.querySelectorAll('.existing-item').forEach(item => {
            const jumlah = parseInt(item.querySelector('.existing-jumlah-input').value) || 0;
            const harga = parseInt(item.querySelector('.existing-harga-input').value) || 0;
            total += jumlah * harga;
        });

        // Calculate from new items
        document.querySelectorAll('.barang-baru').forEach(item => {
            const jumlah = parseInt(item.querySelector('.new-jumlah-field').value) || 0;
            const harga = parseInt(item.querySelector('.new-harga-field').value) || 0;
            total += jumlah * harga;
        });

        // Update display
        document.getElementById('total-semua').textContent = formatRupiah(total);
        document.getElementById('total-hidden').value = total;
    }

    // Initialize Select2
    function initSelect2() {
        $('.searchable-select').select2({
            placeholder: 'Cari barang...',
            allowClear: true
        });
    }
    initSelect2();

    // Add new item
    document.getElementById('tambah-barang').addEventListener('click', function() {
        const select = document.querySelector('.barang-select');
        const selectedOption = select.options[select.selectedIndex];
        
        if (!selectedOption.value) {
            alert('Silakan pilih barang terlebih dahulu');
            return;
        }

        const namaBarang = selectedOption.value;
        const kodeBarang = selectedOption.getAttribute('data-kode');
        const hargaBarang = document.querySelector('.new-harga-input').value || selectedOption.getAttribute('data-harga');
        const jumlahBarang = document.querySelector('.new-jumlah-input').value || 1;

        if (!hargaBarang || isNaN(hargaBarang)) {
            alert('Silakan isi harga satuan');
            return;
        }

        const template = document.getElementById('template-barang-baru');
        const clone = template.content.cloneNode(true);
        const container = document.querySelector('form .space-y-3');
        
        // Fill in the data
        const newItem = clone.querySelector('.barang-baru');
        newItem.querySelector('.new-nama-barang').textContent = namaBarang + ' (' + kodeBarang + ')';
        newItem.querySelector('.new-nama-field').value = namaBarang;
        newItem.querySelector('.new-kode-field').value = kodeBarang;
        newItem.querySelector('.new-jumlah-field').value = jumlahBarang;
        newItem.querySelector('.new-harga-field').value = hargaBarang;
        
        // Add the new item
        container.appendChild(clone);
        
        // Set up event listeners for the new item
        setupNewItemListeners(newItem);
        
        // Reset form
        select.value = '';
        $('.barang-select').trigger('change');
        document.querySelector('.new-harga-input').value = '';
        document.querySelector('.new-jumlah-input').value = '1';
        
        // Calculate total
        calculateTotal();
    });

    // Setup event listeners for a new item
    function setupNewItemListeners(item) {
        const jumlahInput = item.querySelector('.new-jumlah-field');
        const hargaInput = item.querySelector('.new-harga-field');
        const deleteBtn = item.querySelector('.hapus-barang');
        
        // When jumlah or harga changes
        jumlahInput.addEventListener('input', calculateTotal);
        hargaInput.addEventListener('input', calculateTotal);
        
        // Delete button
        deleteBtn.addEventListener('click', function() {
            item.remove();
            calculateTotal();
        });
    }

    // Auto-fill price when item is selected
    document.querySelector('.barang-select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga');
        if (harga) {
            document.querySelector('.new-harga-input').value = harga;
        }
    });

    // Real-time calculation for new item form inputs
    document.querySelector('.new-harga-input').addEventListener('input', function() {
        // Calculate total when editing price in the form (before adding)
        calculateTotal();
    });
    
    document.querySelector('.new-jumlah-input').addEventListener('input', function() {
        // Calculate total when editing quantity in the form (before adding)
        calculateTotal();
    });

    // Set up event listeners for existing items
    function setupExistingItemListeners() {
        document.querySelectorAll('.existing-jumlah-input, .existing-harga-input').forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
        
        document.querySelectorAll('.hapus-item').forEach(button => {
            button.addEventListener('click', function() {
                const itemRow = this.closest('.existing-item');
                if (itemRow) {
                    itemRow.remove();
                    calculateTotal();
                }
            });
        });
    }

    // Set up listeners for existing items
    setupExistingItemListeners();

    // Form submission handler
    document.getElementById('pengeluaran-form').addEventListener('submit', function(e) {
        // Validate at least one item exists
        const existingItems = document.querySelectorAll('.existing-item');
        const newItems = document.querySelectorAll('.barang-baru');
        
        if (existingItems.length === 0 && newItems.length === 0) {
            e.preventDefault();
            alert('Anda harus menambahkan minimal satu item');
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submit-button');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;
        
        // Form will submit normally and redirect based on controller response
    });

    // Initial total calculation
    calculateTotal();
});
</script>
@endpush

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<style>
.barang-baru {
    transition: all 0.3s ease;
}
.hapus-barang, .hapus-item {
    transition: color 0.2s ease;
}
#submit-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
@endpush