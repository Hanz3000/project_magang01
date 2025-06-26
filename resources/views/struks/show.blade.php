@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-4xl w-full space-y-8 bg-white p-8 rounded-3xl shadow-xl transition duration-300 ease-in-out transform hover:shadow-2xl border border-gray-100">

            <!-- Header Section -->
            <div class="text-center mb-6">
                <h1 class="text-4xl font-extrabold text-gray-900 leading-tight tracking-tight drop-shadow-sm">Detail Struk
                    Belanja</h1>
                <p class="mt-2 text-xl text-gray-700 font-light">Informasi lengkap tentang transaksi pembelian Anda.</p>
            </div>

            <!-- Struk Info & Photo Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Informasi Utama Struk</h2>

                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-8 mb-6">
                    <div class="flex-1 space-y-4 text-gray-800">
                        <div>
                            <span class="font-semibold text-gray-700 text-lg flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657m10.614-10.614L13.414 3.1a1.998 1.998 0 00-2.828 0L6.343 6.657m10.614 0L17 7m0 0l.5.5m-.5-.5L17 7z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 21.215l-1.428-1.428m1.428 1.428L13.428 21.215m-1.428 0L12 18m0 3.215l-2.586-2.586A2 2 0 017 17.026V15.5H4.5A1.5 1.5 0 013 14V9a1.5 1.5 0 011.5-1.5H7V4.974A1.5 1.5 0 018.586 3.586L12 0l3.414 3.414A1.5 1.5 0 0117 4.974V6.5h2.5A1.5 1.5 0 0121 8v5a1.5 1.5 0 01-1.5 1.5H17v1.526a1.5 1.5 0 01-2.586 1.06L12 21.215z">
                                    </path>
                                </svg>
                                Nama Toko:
                            </span>
                            <p class="text-2xl font-bold text-gray-900">{{ $struk->nama_toko }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700 text-lg flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                Nomor Struk:
                            </span>
                            <p class="text-2xl font-bold text-gray-900">{{ $struk->nomor_struk }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-700 text-lg flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Tanggal Transaksi:
                            </span>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ date('d M Y', strtotime($struk->tanggal_struk)) }}</p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <span class="text-xl font-medium text-gray-700 flex items-center gap-2">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9m0 3v2.5M12 21a9 9 0 110-18 9 9 0 010 18z">
                                    </path>
                                </svg>
                                Total Belanja:
                            </span>
                            {{-- Menampilkan total harga secara penuh --}}
                            <p class="text-5xl font-extrabold text-emerald-700 mt-2">
                                Rp{{ number_format($struk->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if ($struk->foto_struk)
                        <div class="w-full md:w-80 flex flex-col items-center">
                            <h3 class="font-semibold text-xl mb-4 text-gray-700">Foto Struk</h3>
                            <img src="{{ asset('storage/struk_foto/' . $struk->foto_struk) }}" alt="Foto Struk"
                                class="rounded-xl border border-gray-200 w-full h-auto object-contain shadow-lg cursor-pointer transform transition-all duration-300 hover:scale-105"
                                onclick="openModal('{{ asset('storage/struk_foto/' . $struk->foto_struk) }}')">
                            <button onclick="openModal('{{ asset('storage/struk_foto/' . $struk->foto_struk) }}')"
                                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md text-sm">Lihat
                                Lebih Besar</button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabel Barang Section -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <h3 class="text-3xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Daftar Barang</h3>
                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                    <table class="w-full text-base">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold text-gray-600 uppercase tracking-wider">Nama Barang
                                </th>
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase tracking-wider">Jumlah
                                </th>
                                <th class="px-6 py-4 text-right font-bold text-gray-600 uppercase tracking-wider">Harga
                                    Satuan</th>
                                <th class="px-6 py-4 text-right font-bold text-gray-600 uppercase tracking-wider">Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                // Get raw items, which might be a JSON string, an object, or an array
                                $rawItems = $struk->items;
                                $items = []; // Initialize $items as an empty array

                                if (is_string($rawItems)) {
                                    // If it's a string, try to decode it as an associative array
    $decoded = json_decode($rawItems, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $items = $decoded;
    }
} elseif (is_object($rawItems) || is_array($rawItems)) {
    // If it's already an object or array, iterate and convert nested objects to arrays
                                    foreach ($rawItems as $key => $value) {
                                        if (is_object($value)) {
                                            $items[$key] = (array) $value;
                                        } else {
                                            $items[$key] = $value;
                                        }
                                    }
                                }
                                // If $rawItems was null or an invalid JSON string, $items remains empty.
                            @endphp
                            @forelse($items as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $item['nama'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-bold text-blue-700">
                                        {{ $item['jumlah'] ?? '0' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-800">
                                        Rp{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-indigo-600">
                                        Rp{{ number_format(($item['jumlah'] ?? 0) * ($item['harga'] ?? 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-600 text-lg font-medium">Tidak
                                        ada data barang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('struks.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-700 text-white rounded-xl hover:bg-indigo-800 transition-colors shadow-md text-base font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Struk
                </a>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75" onclick="closeModal()"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <img id="modalImage" src="" alt="Receipt"
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
        // Fungsi untuk membuka modal gambar
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Mencegah scroll pada body saat modal terbuka
        }

        // Fungsi untuk menutup modal gambar
        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden'); // Mengizinkan scroll kembali pada body
        }

        // Menutup modal saat mengklik di luar gambar
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) { // Memastikan klik terjadi pada overlay modal, bukan pada gambar itu sendiri
                closeModal();
            }
        });
    </script>
@endsection
