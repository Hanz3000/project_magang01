@extends('layouts.app')

@section('content')
<div x-data="{ showModal: false }" class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-10">
    <div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-3xl overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-6 text-white">
            <h1 class="text-3xl font-bold">Detail Pengeluaran</h1>
            <p class="mt-1 opacity-90">Informasi lengkap struk barang</p>
        </div>

        <!-- Content -->
        <div class="p-8 space-y-10">

            <!-- Informasi Utama -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <div><span class="font-semibold text-gray-700">Nama Toko:</span> {{ $pengeluaran->nama_toko }}</div>
                    <div><span class="font-semibold text-gray-700">Nomor Struk:</span> {{ $pengeluaran->nomor_struk }}</div>
                <div>
                    <span class="font-semibold text-gray-700">Tanggal Keluar:</span> {{ date('d M Y', strtotime($pengeluaran->tanggal)) }}
                </div>
                <div><span class="font-semibold text-gray-700">Pegawai:</span> {{ $pengeluaran->pegawai?->nama ?? '-' }}</div>
                <div><span class="font-semibold text-gray-700">Jumlah Item:</span> {{ $pengeluaran->jumlah_item }} item</div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="font-semibold text-gray-700">Total:</span>
                        <span class="text-2xl font-bold text-indigo-600">
                            Rp{{ number_format($pengeluaran->total, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700 block mb-2">Bukti Pembayaran:</span>
                        @if ($pengeluaran->bukti_pembayaran)
                        <img
                            src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}"
                            alt="Bukti Pembayaran"
                            class="w-64 h-40 object-cover rounded-xl border cursor-pointer hover:opacity-80 transition"
                            @click="showModal = true">
                        @else
                        <span class="italic text-gray-500">Tidak ada bukti</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Barang -->
            <div>
                <h2 class="text-xl font-semibold text-blue-600 mb-4">Daftar Barang</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-slate-200 rounded-xl">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-4 py-3 border">No</th>
                                <th class="px-4 py-3 border text-left">Nama Barang</th>
                                <th class="px-4 py-3 border text-center">Jumlah</th>
                                <th class="px-4 py-3 border text-right">Harga Satuan</th>
                                <th class="px-4 py-3 border text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengeluaran->daftar_barang as $index => $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 border">{{ $item['nama'] }}</td>
                                <td class="px-4 py-2 border text-center">{{ $item['jumlah'] }}</td>
                                <td class="px-4 py-2 border text-right">
                                    Rp{{ number_format($item['harga'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 border text-right">
                                    Rp{{ number_format(($item['jumlah'] ?? 0) * ($item['harga'] ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div>
                <a href="{{ route('pengeluarans.index') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl shadow transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Bukti Pembayaran -->
    @if ($pengeluaran->bukti_pembayaran)
    <div
        x-show="showModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50"
        x-transition
        x-cloak>
        <div class="bg-white rounded-3xl p-6 max-w-3xl w-full relative">
            <button
                @click="showModal = false"
                class="absolute top-3 right-3 text-gray-600 hover:text-black text-3xl">
                &times;
            </button>
            <h2 class="text-xl font-semibold mb-4">Bukti Pembayaran</h2>
            <img
                src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}"
                alt="Bukti Pembayaran"
                class="w-full rounded-xl object-contain">
        </div>
    </div>
    @endif
</div>
@endsection
