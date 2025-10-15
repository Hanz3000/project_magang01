@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-4 text-white">
            <h1 class="text-xl sm:text-2xl font-bold">Detail Struk</h1>
            <p class="text-sm opacity-90">Informasi lengkap struk barang</p>
        </div>

        <!-- Content -->
        <div class="p-4 space-y-6">
            <!-- Info Struk -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 shadow-sm">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Nama Toko</p>
                        <p class="text-lg font-bold text-gray-800">{{ $struk->nama_toko }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Nomor Struk</p>
                        <p class="text-lg font-bold text-gray-800">{{ $struk->nomor_struk }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Masuk</p>
                        <p class="text-md font-semibold text-gray-800">{{ date('d M Y', strtotime($struk->tanggal_struk)) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total Pembayaran</p>
                        <p class="text-xl font-extrabold text-indigo-600">
                            Rp{{ number_format($struk->total_harga, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                @if ($struk->foto_struk)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500 mb-2">Foto Struk</p>
                    <img src="{{ asset('storage/struk_foto/' . $struk->foto_struk) }}"
                         alt="Foto Struk"
                         class="rounded-lg border-2 border-gray-200 shadow-md w-full max-w-[300px] cursor-pointer transition-all duration-300 hover:scale-105"
                         onclick="openModal('{{ asset('storage/struk_foto/' . $struk->foto_struk) }}')">
                </div>
                @endif
            </div>

            <!-- Daftar Barang -->
            <div class="bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-100">
                    <h3 class="text-md font-semibold text-gray-800 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M4 6h16M4 18h16" />
                        </svg>
                        <span>Daftar Barang</span>
                    </h3>
                </div>

                <div class="p-2">
                    <table class="w-full border-collapse text-sm text-gray-700 table-fixed responsive-table">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
                                <th class="border px-2 py-1 text-center font-semibold w-[40px]">No</th>
                                <th class="border px-2 py-1 text-left font-semibold">Kode</th>
                                <th class="border px-2 py-1 text-left font-semibold">Nama</th>
                                <th class="border px-2 py-1 text-center font-semibold">Jumlah</th>
                                <th class="border px-2 py-1 text-center font-semibold">Harga</th>
                                <th class="border px-2 py-1 text-center font-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
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

                            @foreach ($items as $index => $item)
                                @php
                                    $kodeBarang = $item['kode_barang'] ?? ($item['nama'] ?? null);
                                    $nama = $masterBarang[$kodeBarang]->nama_barang ?? $item['nama'] ?? $kodeBarang;
                                    $jumlah = (int) ($item['jumlah'] ?? 0);
                                    $harga = (int) ($item['harga'] ?? 0);
                                    $subtotal = $jumlah * $harga;
                                @endphp
                                <tr>
                                    <td class="border px-2 py-1 text-center" data-label="No">{{ $index + 1 }}</td>
                                    <td class="border px-2 py-1 font-mono text-gray-800" data-label="Kode">{{ $kodeBarang }}</td>
                                    <td class="border px-2 py-1 font-semibold text-gray-900" data-label="Nama">{{ $nama }}</td>
                                    <td class="border px-2 py-1 text-center" data-label="Jumlah">{{ $jumlah }}</td>
                                    <td class="border px-2 py-1 text-center text-indigo-600 font-medium" data-label="Harga">Rp{{ number_format($harga, 0, ',', '.') }}</td>
                                    <td class="border px-2 py-1 text-center text-indigo-600 font-semibold" data-label="Subtotal">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol kembali -->
            <div class="flex justify-start">
                <a href="{{ route('struks.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition select-none">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gambar -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center">
    <div class="relative w-full max-w-[90vw]">
        <img id="modalImage" src="" class="max-h-[80vh] w-full rounded shadow-lg select-none">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-white hover:text-gray-300 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) closeModal();
    });
</script>

<style>
    /* Tabel normal */
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        word-wrap: break-word;
    }

    /* Responsif berubah jadi model turun */
    @media (max-width: 768px) {
        thead {
            display: none;
        }
        tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            border-bottom: 1px solid #f3f4f6;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }
        td:last-child {
            border-bottom: none;
        }
        td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #4b5563;
            text-transform: capitalize;
        }
        .max-w-4xl {
            max-width: 95%;
        }
    }
</style>
@endsection
