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
                    </div>
                    @endforeach
                </div>
            </div> <!-- 🔴 Penutup daftar barang -->

            {{-- Total --}}
            <div>
                <label class="block mb-1 font-medium">Total Pembayaran</label>
                <input type="text" class="w-full border rounded px-3 py-2 text-xl font-bold text-right"
                    value="Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}" readonly>
            </div>

            {{-- Bukti Pembayaran --}}
            <div x-data="{ open: false }">
                <label class="block mb-1 font-medium">Bukti Pembayaran</label>

                @if ($pengeluaran->bukti_pembayaran)
                <img @click="open = true"
                    src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}"
                    alt="Bukti Pembayaran"
                    class="h-40 object-contain border rounded cursor-pointer hover:opacity-80 transition">

                <!-- Modal -->
                <div x-show="open" x-transition
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-75 z-50">
                    <div @click.away="open = false" class="max-w-3xl mx-auto">
                        <img src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}"
                            class="rounded shadow-lg max-h-[80vh]">
                        <button @click="open = false"
                            class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Tutup
                        </button>
                    </div>
                </div>
                @else
                <p class="text-sm text-gray-500">Tidak ada bukti pembayaran</p>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-4">
                <a href="{{ route('pengeluarans.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>

        </form> 
    </div>
</div>
@endsection
