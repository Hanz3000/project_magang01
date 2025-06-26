@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">✏️ Edit Struk</h2>

        {{-- Error --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-6">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('struks.update', $struk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Informasi Umum --}}
            <div class="space-y-3">
                <div>
                    <label class="block font-medium text-gray-700">Nama Toko</label>
                    <input type="text" name="nama_toko" class="w-full p-2 border rounded"
                        value="{{ old('nama_toko', $struk->nama_toko) }}" required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Nomor Struk</label>
                    <input type="text" name="nomor_struk" class="w-full p-2 border rounded"
                        value="{{ old('nomor_struk', $struk->nomor_struk) }}" required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Tanggal Struk</label>
                    <input type="date" name="tanggal_struk" class="w-full p-2 border rounded"
                        value="{{ old('tanggal_struk', $struk->tanggal_struk) }}" required>
                </div>
            </div>

            <hr class="my-4">

            {{-- Items --}}
            <div>
                <label class="block font-medium text-gray-700 mb-2">📦 Daftar Barang</label>
                <div id="items-wrapper" class="space-y-3">
                    @foreach ($struk->items as $index => $item)
                        <div class="flex flex-wrap gap-2 item-row">
                            <div class="flex-1 relative">
                                <input type="text" name="items[{{ $index }}][nama]" placeholder="Nama Barang"
                                    class="w-full p-2 border rounded item-name-input" value="{{ $item->nama }}"
                                    data-index="{{ $index }}" autocomplete="off">
                                <div id="item-suggestions-{{ $index }}"
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden">
                                    <!-- Suggestions will be populated here -->
                                </div>
                            </div>
                            <input type="number" name="items[{{ $index }}][jumlah]" placeholder="Jumlah"
                                class="p-2 border rounded w-24" value="{{ $item->jumlah }}">
                            <input type="number" name="items[{{ $index }}][harga]" placeholder="Harga"
                                class="p-2 border rounded w-32" value="{{ $item->harga }}">
                            <button type="button" class="remove-item text-red-500 font-bold px-2">×</button>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-item" class="mt-2 text-blue-600 hover:underline text-sm">+ Tambah
                    Item</button>
            </div>

            <hr class="my-4">

            {{-- Total Harga --}}
            <div>
                <label class="block font-medium text-gray-700">💰 Total Harga</label>
                <input type="number" name="total_harga" class="w-full p-2 border rounded" placeholder="Total Harga"
                    value="{{ old('total_harga', $struk->total_harga) }}" required>
            </div>

            {{-- Foto --}}
            <div>
                <label class="block font-medium text-gray-700">🖼️ Foto Struk</label>
                <input type="file" name="foto_struk" class="w-full p-2 border rounded">
                @if ($struk->foto_struk)
                    <div class="mt-2">
                        <p class="text-sm text-gray-600 mb-1">Foto saat ini:</p>
                        <img src="{{ asset('storage/' . $struk->foto_struk) }}" width="120" class="rounded border">
                    </div>
                @endif
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                    💾 Perbarui Struk
                </button>
            </div>
        </form>
    </div>

    {{-- Tambah item via JS --}}
    <script>
        // Function to fetch item suggestions
        function fetchItemSuggestions(query, index) {
            if (query.length < 2) {
                document.getElementById(`item-suggestions-${index}`).classList.add('hidden');
                return;
            }

            fetch(`/api/items/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const suggestionsContainer = document.getElementById(`item-suggestions-${index}`);
                    suggestionsContainer.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(item => {
                            const suggestion = document.createElement('div');
                            suggestion.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer';
                            suggestion.textContent = item.nama;
                            suggestion.addEventListener('click', () => {
                                const input = document.querySelector(
                                    `.item-name-input[data-index="${index}"]`);
                                input.value = item.nama;
                                suggestionsContainer.classList.add('hidden');

                                // You can also auto-fill the price if available
                                const priceInput = input.closest('.item-row').querySelector(
                                    'input[name$="[harga]"]');
                                if (priceInput && item.harga) {
                                    priceInput.value = item.harga;
                                }
                            });
                            suggestionsContainer.appendChild(suggestion);
                        });
                        suggestionsContainer.classList.remove('hidden');
                    } else {
                        suggestionsContainer.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching suggestions:', error);
                    document.getElementById(`item-suggestions-${index}`).classList.add('hidden');
                });
        }

        // Initialize event listeners for existing inputs
        document.querySelectorAll('.item-name-input').forEach(input => {
            const index = input.dataset.index;

            input.addEventListener('input', (e) => {
                fetchItemSuggestions(e.target.value, index);
            });

            input.addEventListener('focus', (e) => {
                if (e.target.value.length >= 2) {
                    fetchItemSuggestions(e.target.value, index);
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !document.getElementById(`item-suggestions-${index}`)
                    .contains(e.target)) {
                    document.getElementById(`item-suggestions-${index}`).classList.add('hidden');
                }
            });
        });

        // Add new item
        document.getElementById('add-item').addEventListener('click', function() {
            const wrapper = document.getElementById('items-wrapper');
            const index = wrapper.querySelectorAll('.item-row').length;
            const div = document.createElement('div');
            div.className = 'flex flex-wrap gap-2 item-row';
            div.innerHTML = `
                <div class="flex-1 relative">
                    <input type="text" name="items[${index}][nama]" placeholder="Nama Barang"
                        class="w-full p-2 border rounded item-name-input"
                        data-index="${index}"
                        autocomplete="off">
                    <div id="item-suggestions-${index}" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden">
                        <!-- Suggestions will be populated here -->
                    </div>
                </div>
                <input type="number" name="items[${index}][jumlah]" placeholder="Jumlah" class="p-2 border rounded w-24">
                <input type="number" name="items[${index}][harga]" placeholder="Harga" class="p-2 border rounded w-32">
                <button type="button" class="remove-item text-red-500 font-bold px-2">×</button>
            `;
            wrapper.appendChild(div);

            // Initialize event listeners for the new input
            const newInput = div.querySelector('.item-name-input');
            newInput.addEventListener('input', (e) => {
                fetchItemSuggestions(e.target.value, index);
            });

            newInput.addEventListener('focus', (e) => {
                if (e.target.value.length >= 2) {
                    fetchItemSuggestions(e.target.value, index);
                }
            });
        });

        // Remove item
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
                hitungTotalHarga();
            }
        });

        // Calculate total price
        function hitungTotalHarga() {
            let total = 0;
            document.querySelectorAll('#items-wrapper .item-row').forEach(row => {
                const jumlah = parseFloat(row.querySelector('[name$="[jumlah]"]')?.value) || 0;
                const harga = parseFloat(row.querySelector('[name$="[harga]"]')?.value) || 0;
                total += jumlah * harga;
            });
            document.querySelector('input[name="total_harga"]').value = total;
        }

        // Trigger hitung total saat input jumlah/harga berubah
        document.addEventListener('input', function(e) {
            if (e.target.name.includes('[jumlah]') || e.target.name.includes('[harga]')) {
                hitungTotalHarga();
            }
        });

        // Hitung ulang setelah item ditambahkan
        document.getElementById('add-item').addEventListener('click', hitungTotalHarga);

        // Hitung ulang saat item dihapus
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
                hitungTotalHarga();
            }
        });

        // Jalankan saat halaman dimuat untuk inisialisasi
        window.addEventListener('DOMContentLoaded', () => {
            hitungTotalHarga();
        });
    </script>
@endsection
