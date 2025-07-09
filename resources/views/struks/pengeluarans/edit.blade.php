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

            {{-- 🔴 Existing Items --}}
            <div class="space-y-3" id="items-container">
                <div class="grid grid-cols-4 gap-4 font-medium text-gray-700 pb-2 border-b">
                    <div>Nama Barang</div>
                    <div>Jumlah</div>
                    <div>Harga Satuan</div>
                    <div>Aksi</div>
                </div>

                @foreach ($pengeluaran->daftar_barang as $index => $item)
                <div class="grid grid-cols-4 gap-4 items-center py-3 border-b existing-item">
                    @php
                    $kodeBarang = $item['nama']; // karena kamu simpan kode_barang di field 'nama'
                    $namaBarang = \App\Models\Barang::where('kode_barang', $kodeBarang)->value('nama_barang') ?? $kodeBarang;
                    @endphp

                    <div class="text-sm">
                        <div class="text-gray-900 font-semibold">{{ $namaBarang }}</div>
                        <div class="text-gray-500 text-xs">Kode: {{ $kodeBarang }}</div>
                    </div>
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
                        <button type="button" class="hapus-item text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Hidden fields --}}
            <input type="hidden" name="nama_toko" value="{{ $pengeluaran->nama_toko }}">
            <input type="hidden" name="nomor_struk" value="{{ $pengeluaran->nomor_struk }}">

            {{-- 🔴 Tanggal --}}
            <div>
                <label class="block mb-1 font-medium">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal" class="w-full border rounded px-3 py-2"
                    value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
            </div>

            {{-- 🔴 Pegawai --}}
            <div>
                <label class="block mb-1 font-medium">Pegawai</label>
                <select name="pegawai_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach ($pegawais as $pegawai)
                    <option value="{{ $pegawai->id }}" {{ $pegawai->id == $pengeluaran->pegawai_id ? 'selected' : '' }}>
                        {{ $pegawai->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- 🔴 Tambah Item Baru --}}
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-medium">+ Tambah Item Baru</h3>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-3">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Cari barang atau pilih dari daftar</label>
                        <select class="w-full border rounded px-3 py-2 barang-select">
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

            {{-- 🔴 Total --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-lg">Total Semua Item</span>
                    <span id="total-semua" class="font-bold text-xl">Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}</span>
                </div>
                <input type="hidden" name="total" id="total-hidden" value="{{ $pengeluaran->total }}">
            </div>

            {{-- 🔴 Action Buttons --}}
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

        {{-- Template item baru --}}
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

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tambahBtn = document.getElementById('tambah-barang');
        const template = document.getElementById('template-barang-baru');
        const container = document.getElementById('items-container');

        function calculateTotal() {
            let total = 0;

            document.querySelectorAll('.existing-item').forEach(item => {
                const jumlah = parseInt(item.querySelector('.existing-jumlah-input').value) || 0;
                const harga = parseInt(item.querySelector('.existing-harga-input').value) || 0;
                total += jumlah * harga;
            });

            document.querySelectorAll('.barang-baru').forEach(item => {
                const jumlah = parseInt(item.querySelector('.new-jumlah-field').value) || 0;
                const harga = parseInt(item.querySelector('.new-harga-field').value) || 0;
                total += jumlah * harga;
            });

            document.getElementById('total-semua').textContent = 'Rp ' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('total-hidden').value = total;
        }

        // Existing items: hapus
        container.addEventListener('click', function(e) {
            if (e.target.closest('.hapus-item')) {
                e.target.closest('.existing-item').remove();
                calculateTotal();
            }
        });

        // Tambah barang baru
        tambahBtn.addEventListener('click', function() {
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

            const clone = template.content.cloneNode(true);
            const newItem = clone.querySelector('.barang-baru');

            newItem.querySelector('.new-nama-barang').textContent = namaBarang + ' (' + kodeBarang + ')';
            newItem.querySelector('.new-nama-field').value = namaBarang;
            newItem.querySelector('.new-kode-field').value = kodeBarang;
            newItem.querySelector('.new-jumlah-field').value = jumlahBarang;
            newItem.querySelector('.new-harga-field').value = hargaBarang;

            // Delete new item
            newItem.querySelector('.hapus-barang').addEventListener('click', function() {
                newItem.remove();
                calculateTotal();
            });

            container.appendChild(clone);

            select.value = '';
            document.querySelector('.new-harga-input').value = '';
            document.querySelector('.new-jumlah-input').value = '1';

            calculateTotal();
        });

        document.querySelectorAll('.existing-jumlah-input, .existing-harga-input').forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        calculateTotal();
    });
</script>
@endpush