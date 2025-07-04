@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-6 text-white">
            <h2 class="text-xl font-bold">Edit Pengeluaran</h2>
            <p class="text-sm opacity-80">Ubah, tambah, atau hapus item dalam pengeluaran</p>
        </div>

        <form action="{{ route('pengeluarans.update', $pengeluaran->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Toko --}}
            <div>
                <label class="block mb-1 font-medium">Nama Toko</label>
            <input type="text" name="nama_toko" class="w-full border rounded px-3 py-2 bg-gray-100" 
                value="{{ old('nama_toko', $pengeluaran->nama_toko) }}" readonly>

            </div>

            {{-- Nomor Struk --}}
            <div>
                <label class="block mb-1 font-medium">Nomor Struk</label>
                <input type="text" name="nomor_struk" class="w-full border rounded px-3 py-2 bg-gray-100"
                    value="{{ old('nomor_struk', $pengeluaran->nomor_struk) }}" readonly>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block mb-1 font-medium">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal" class="w-full border rounded px-3 py-2"
                    value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
            </div>

            {{-- Pegawai --}}
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

            {{-- Daftar Barang --}}
            <div>
                <label class="block mb-2 font-medium">Detail Barang</label>
                <div class="space-y-3">
                    @foreach ($pengeluaran->daftar_barang as $index => $item)
                    <div class="flex items-center gap-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex-1 grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm text-gray-500">Nama Barang</label>
                                <input type="text" name="nama_barang[]" value="{{ $item['nama'] }}"
                                    class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-500">Jumlah</label>
                                <input type="text" name="jumlah[]" value="{{ $item['jumlah'] }}"
                                    class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-500">Harga Satuan</label>
                                <input type="text" value="Rp {{ number_format($item['harga'], 0, ',', '.') }}"
                                    class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                            </div>
                        </div>
                        <button type="button"
                            class="text-red-500 hover:text-red-700 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>

            {{-- Total --}}
            <div>
                <label class="block mb-1 font-medium">Total Pembayaran</label>
                <input type="text" class="w-full border rounded px-3 py-2 text-xl font-bold text-right"
                    value="Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}" readonly>
            </div>

            {{-- Bukti Pembayaran --}}
            <div>
                <label class="block mb-1 font-medium">Bukti Pembayaran</label>
                @if ($pengeluaran->bukti_pembayaran)
                <img src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                    class="h-40 object-contain border rounded">
                @else
                <p class="text-sm text-gray-500">Tidak ada bukti pembayaran</p>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('pengeluarans.index') }}"
                    class="px-4 py-2 rounded border border-gray-300 bg-white hover:bg-gray-50">Batal</a>
                <button type="submit"
                    class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
