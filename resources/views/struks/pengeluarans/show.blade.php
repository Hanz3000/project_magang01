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
                    <div><span class="font-semibold text-gray-700">Nama Spk:</span> {{ $pengeluaran->nama_toko }}</div>
                    <div><span class="font-semibold text-gray-700">Nomor Struk:</span> {{ $pengeluaran->nomor_struk }}</div>
                    <div>
                        <span class="font-semibold text-gray-700">Tanggal Keluar:</span> {{ date('d M Y', strtotime($pengeluaran->tanggal)) }}
                    </div>
                    <div><span class="font-semibold text-gray-700">Pegawai:</span> {{ $pengeluaran->pegawai?->nama ?? '-' }}</div>
                    <div><span class="font-semibold text-gray-700">Jumlah Item:</span> {{ $pengeluaran->jumlah_item }} item</div>
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
                                <th class="px-4 py-3 border text-left">Kode Barang</th>
                                <th class="px-4 py-3 border text-left">Nama Barang</th>
                                <th class="px-4 py-3 border text-center">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pengeluaran->daftar_barang as $index => $item)
                            @php
                            $kode = $item['nama'] ?? '-';
                            $barang = $masterBarang[$kode] ?? null;
                            $namaBarang = $barang?->nama_barang ?? $kode;
                            $jumlah = $item['jumlah'] ?? 0;
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 border text-gray-700">{{ $kode }}</td>
                                <td class="px-4 py-2 border">{{ $namaBarang }}</td>
                                <td class="px-4 py-2 border text-center">{{ $jumlah }}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-4">
                <a href="{{ route('pengeluarans.index') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl shadow transition">
                    Kembali
                </a>
                <button
                    onclick="printStruk()"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl shadow transition">
                    Cetak Struk
                </button>
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

    <!-- Area Struk Cetak -->
    <div id="print-area" class="hidden">
        <div style="font-family: Arial, sans-serif; padding: 20px;">
            <h2 style="text-align: center;">Struk Pengeluaran</h2>
            <hr style="margin: 10px 0;">
            <p><strong>Nama Spk:</strong> {{ $pengeluaran->nama_toko }}</p>
            <p><strong>Nomor Struk:</strong> {{ $pengeluaran->nomor_struk }}</p>
            <p><strong>Tanggal:</strong> {{ date('d M Y', strtotime($pengeluaran->tanggal)) }}</p>
            <p><strong>Pegawai:</strong> {{ $pengeluaran->pegawai?->nama ?? '-' }}</p>
            <p><strong>Jumlah Item:</strong> {{ $pengeluaran->jumlah_item }} item</p>
    

            <h4 style="margin-top: 20px;">Daftar Barang</h4>
            <table border="1" cellpadding="8" cellspacing="0" width="100%" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengeluaran->daftar_barang as $index => $item)
                    @php
                        $kode = $item['nama'] ?? '-';
                        $barang = $masterBarang[$kode] ?? null;
                        $namaBarang = $barang?->nama_barang ?? $kode;
                        $jumlah = $item['jumlah'] ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kode }}</td>
                        <td>{{ $namaBarang }}</td>
                        <td>{{ $jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p style="text-align:center; margin-top: 30px;">--- Terima Kasih ---</p>
        </div>
    </div>
</div>

<!-- Script Cetak -->
<script>
    function printStruk() {
        const printContents = document.getElementById("print-area").innerHTML;
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
@endsection
