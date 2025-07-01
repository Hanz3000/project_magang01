@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-6 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2 select-none">
                    <span class="text-3xl"></span>
                    <div>
                        <h1 class="text-3xl font-bold">Detail Struk</h1>
                        <p class="mt-1 opacity-90">Informasi lengkap struk barang</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">

            <!-- Store Info -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nama Toko</p>
                            <p class="text-xl font-bold text-gray-800">{{ $struk->nama_toko }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nomor Struk</p>
                            <p class="text-xl font-bold text-gray-800">{{ $struk->nomor_struk }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Transaksi</p>
                            <p class="text-xl font-bold">{{ date('d M Y', strtotime($struk->tanggal_struk)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pembayaran</p>
                            <p class="text-2xl font-extrabold text-indigo-600">Rp{{ number_format($struk->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                @if ($struk->foto_struk)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-500 mb-3">Foto Struk</p>
                    <img src="{{ asset('storage/struk_foto/' . $struk->foto_struk) }}"
                        alt="Foto Struk"
                        class="rounded-lg border-2 border-gray-200 shadow-md w-full max-w-xs cursor-pointer transition-all duration-300"
                        onclick="openModal('{{ asset('storage/struk_foto/' . $struk->foto_struk) }}')">
                </div>
                @endif
            </div>

            <!-- Items Table -->
            <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-200 bg-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 18h16" />
                        </svg>
                        <span>Daftar Barang</span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                            $items = [];
                            $rawItems = $struk->items;

                            if (is_string($rawItems)) {
                            $decoded = json_decode($rawItems, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $items = $decoded;
                            }
                            } elseif (is_object($rawItems) || is_array($rawItems)) {
                            foreach ($rawItems as $key => $value) {
                            $items[$key] = is_object($value) ? (array) $value : $value;
                            }
                            }
                            @endphp

                            @forelse ($items as $item)
                            @php
                            $nama = $item['nama'] ?? 'N/A';
                            $jumlah = (int) ($item['jumlah'] ?? 0);
                            $harga = (int) ($item['harga'] ?? 0);
                            $subtotal = $jumlah * $harga;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $nama }}</td>
                                <td class="px-6 py-4 text-sm text-center text-gray-500">{{ $jumlah }}</td>
                                <td class="px-6 py-4 text-sm text-center font-semibold text-indigo-600">Rp{{ number_format($harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-center font-semibold text-indigo-600">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada barang
                                </td>
                            </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>

            <!-- Back button -->
            <div class="flex justify-start">
                <a href="{{ route('struks.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition select-none">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center">
    <div class="relative">
        <img id="modalImage" src="" class="max-h-[80vh] rounded shadow-lg select-none">

        <!-- Tombol close dengan ikon -->
        <button onclick="closeModal()" class="absolute top-2 right-2 text-white hover:text-gray-300 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>


<script>
    function openModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
            closeModal();
        }
    });
</script>
@endsection