@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h1 class="text-xl font-semibold text-gray-900">Edit Data Pengeluaran</h1>
            </div>

            <form action="{{ route('pengeluarans.update', $pengeluaran->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Informasi Dasar (Read-only) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Nama Toko --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                        <input type="text" value="{{ $pengeluaran->nama_toko }}"
                            class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                    </div>

                    {{-- Nomor Struk --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Struk</label>
                        <input type="text" value="{{ $pengeluaran->nomor_struk }}"
                            class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                            Pengeluaran</label>
                        <input type="date" id="tanggal" name="tanggal"
                            value="{{ old('tanggal', $pengeluaran->tanggal) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>
                </div>

                {{-- Pegawai --}}
                <div>
                    <label for="pegawai_id" class="block text-sm font-medium text-gray-700 mb-1">Pegawai</label>
                    <select id="pegawai_id" name="pegawai_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach ($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}"
                                {{ $pegawai->id == $pengeluaran->pegawai_id ? 'selected' : '' }}>
                                {{ $pegawai->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Daftar Barang (Read-only) --}}
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Barang</h3>

                    <div id="items-container" class="space-y-4">
                        @foreach ($pengeluaran->daftar_barang as $item)
                            <div class="item-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    {{-- Nama Barang --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                                        <input type="text" value="{{ $item['nama'] }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                                    </div>

                                    {{-- Jumlah --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                        <input type="text" value="{{ $item['jumlah'] }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                                    </div>

                                    {{-- Harga Satuan --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                                        <input type="text" value="Rp {{ number_format($item['harga'], 0, ',', '.') }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                                    </div>

                                    {{-- Subtotal --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                                        <input type="text"
                                            value="Rp {{ number_format($item['jumlah'] * $item['harga'], 0, ',', '.') }}"
                                            class="w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Total Pembayaran (Read-only) --}}
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-medium text-gray-900">Total Pembayaran</span>
                        <input type="text" class="text-xl font-bold text-right bg-transparent border-none"
                            value="Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}" readonly>
                    </div>
                </div>

                {{-- Tampilkan Foto (Read-only) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                    @if ($pengeluaran->bukti_pembayaran)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $pengeluaran->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                                class="h-40 object-contain border border-gray-200 rounded-md">
                        </div>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Tidak ada bukti pembayaran</p>
                    @endif
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-4 pt-4">
                    <a href="{{ route('pengeluarans.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kembali
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
