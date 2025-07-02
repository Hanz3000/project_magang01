@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Edit Pengeluaran</h2>

    <form action="{{ route('pengeluarans.update', $pengeluaran->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama Toko --}}
        <div class="mb-3">
            <label class="block mb-1">Nama Toko</label>
            <input type="text" name="nama_toko" class="w-full border rounded px-3 py-2"
                value="{{ old('nama_toko', $pengeluaran->nama_toko) }}">
        </div>

        {{-- Nomor Struk --}}
        <div class="mb-3">
            <label class="block mb-1">Nomor Struk</label>
            <input type="text" name="nomor_struk" class="w-full border rounded px-3 py-2"
                value="{{ old('nomor_struk', $pengeluaran->nomor_struk) }}">
        </div>

        {{-- Tanggal --}}
        <div class="mb-3">
            <label class="block mb-1">Tanggal Pengeluaran</label>
            <input type="date" name="tanggal" class="w-full border rounded px-3 py-2"
                value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
        </div>

        {{-- Pegawai --}}
        <div class="mb-3">
            <label class="block mb-1">Pegawai</label>
            <select name="pegawai_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Pilih Pegawai --</option>
                @foreach ($pegawais as $pegawai)
                <option value="{{ $pegawai->id }}" {{ $pegawai->id == $pengeluaran->pegawai_id ? 'selected' : '' }}>
                    {{ $pegawai->nama }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Daftar Barang --}}
        <div class="mb-4">
            <label class="block mb-1">Detail Barang</label>
            <div id="items-container" class="space-y-4">
                @foreach ($pengeluaran->daftar_barang as $item)
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Barang</label>
                            <input type="text" name="nama_barang[]" value="{{ $item['nama'] }}"
                                class="w-full rounded border-gray-300 bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Jumlah</label>
                            <input type="text" name="jumlah[]" value="{{ $item['jumlah'] }}"
                                class="w-full rounded border-gray-300 bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Harga Satuan</label>
                            <input type="text" value="Rp {{ number_format($item['harga'], 0, ',', '.') }}"
                                class="w-full rounded border-gray-300 bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Subtotal</label>
                            <input type="text"
                                value="Rp {{ number_format($item['jumlah'] * $item['harga'], 0, ',', '.') }}"
                                class="w-full rounded border-gray-300 bg-gray-100" readonly>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Total --}}
        <div class="mb-4">
            <label class="block mb-1">Total Pembayaran</label>
            <input type="text" class="w-full border rounded px-3 py-2 text-xl font-bold text-right"
                value="Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}" readonly>
        </div>

        {{-- Bukti Pembayaran --}}
        <div class="mb-4">
            <label class="block mb-1">Bukti Pembayaran</label>
            @if ($pengeluaran->bukti_pembayaran)
            <img src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                class="h-40 object-contain border rounded">
            @else
            <p class="text-sm text-gray-500">Tidak ada bukti pembayaran</p>
            @endif
        </div>

        

        {{-- Tombol Aksi --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('pengeluarans.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Kembali
            </a>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection