@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20">

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
                            {{-- Dropdown Nama Barang --}}
                            <div>
                                <label class="block text-sm text-gray-500">Nama Barang</label>
                                <select name="items[{{ $index }}][nama]" class="w-full border rounded px-3 py-2 searchable-select barang-select" data-index="{{ $index }}">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $barang)
                                    <option value="{{ $barang->nama_barang }}"
                                        data-harga="{{ $barang->harga_satuan }}"
                                        {{ $item['nama'] === $barang->nama_barang ? 'selected' : '' }}>
                                        {{ $barang->nama_barang }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jumlah --}}
                            <div>
                                <label class="block text-sm text-gray-500">Jumlah</label>
                                <input type="number" name="items[{{ $index }}][jumlah]" value="{{ $item['jumlah'] }}"
                                    class="w-full border rounded px-3 py-2 jumlah-input" data-index="{{ $index }}">
                            </div>

                            {{-- Harga --}}
                            <div>
                                <label class="block text-sm text-gray-500">Harga Satuan</label>
                                <input type="number" name="items[{{ $index }}][harga]" value="{{ $item['harga'] }}"
                                    class="w-full border rounded px-3 py-2 harga-input" data-index="{{ $index }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Total --}}
            <div>
                <label class="block mb-1 font-medium">Total Pembayaran</label>
                <input type="text" id="total-pembayaran"
                    class="w-full border rounded px-3 py-2 text-xl font-bold text-right bg-gray-100"
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
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.searchable-select').select2({
            placeholder: 'Cari barang...',
            allowClear: true
        });

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.jumlah-input').forEach(function(jumlahInput) {
                const index = jumlahInput.dataset.index;
                const jumlah = parseInt(jumlahInput.value) || 0;
                const harga = parseInt(document.querySelector('.harga-input[data-index="' + index + '"]').value) || 0;
                total += jumlah * harga;
            });

            document.getElementById('total-pembayaran').value = formatRupiah(total);
        }

        // Saat pilih barang, set harga otomatis
        document.querySelectorAll('.barang-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const harga = this.options[this.selectedIndex].getAttribute('data-harga');
                const index = this.dataset.index;
                const hargaInput = document.querySelector('.harga-input[data-index="' + index + '"]');
                if (harga && hargaInput) {
                    hargaInput.value = harga;
                }
                updateTotal();
            });
        });

        // Saat ubah jumlah atau harga, update total
        document.querySelectorAll('.jumlah-input, .harga-input').forEach(function(input) {
            input.addEventListener('input', updateTotal);
        });

        // Initial total calculation on page load
        updateTotal();
    });
</script>

@endpush

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
@endpush