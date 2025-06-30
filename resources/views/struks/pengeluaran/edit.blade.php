@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Edit Pengeluaran</h1>

    <form action="{{ route('pengeluarans.update', $pengeluaran->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Pilih Pegawai --}}
        <div>
            <label class="block font-medium mb-1">Pilih Pegawai</label>
            <select name="pegawai_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Pilih Pegawai --</option>
                @foreach ($pegawais as $pegawai)
                    <option value="{{ $pegawai->id }}" {{ $pegawai->id == $pengeluaran->pegawai_id ? 'selected' : '' }}>
                        {{ $pegawai->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Info Header --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block mb-1">Nama Toko</label>
                <input type="text" name="nama_toko" class="w-full border rounded px-3 py-2" value="{{ old('nama_toko', $pengeluaran->nama_toko) }}" required>
            </div>
            <div>
                <label class="block mb-1">Nomor Struk</label>
                <input type="text" name="nomor_struk" class="w-full border rounded px-3 py-2" value="{{ old('nomor_struk', $pengeluaran->nomor_struk) }}" required>
            </div>
            <div>
                <label class="block mb-1">Tanggal</label>
                <input type="date" name="tanggal" class="w-full border rounded px-3 py-2" value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
            </div>
        </div>

        {{-- Daftar Barang --}}
        <div class="border rounded p-4">
            <h3 class="font-bold mb-2">Detail Barang</h3>
            <div id="items-container">
                @foreach ($pengeluaran->daftar_barang as $index => $item)
                    <div class="item-item space-y-2 mb-4">
                        <div>
                            <label class="block mb-1">Nama Barang</label>
                            <input type="text" name="items[{{ $index }}][nama]" value="{{ $item['nama'] }}" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block mb-1">Jumlah</label>
                            <input type="number" name="items[{{ $index }}][jumlah]" value="{{ $item['jumlah'] }}" class="w-full border rounded px-3 py-2 jumlah" min="1" oninput="hitungTotal()" required>
                        </div>
                        <div>
                            <label class="block mb-1">Harga Satuan</label>
                            <input type="number" name="items[{{ $index }}][harga]" value="{{ $item['harga'] }}" class="w-full border rounded px-3 py-2 harga" min="0" oninput="hitungTotal()" required>
                        </div>
                        <div>
                            <label class="block mb-1">Subtotal</label>
                            <input type="text" class="w-full border rounded px-3 py-2 subtotal" value="Rp {{ number_format($item['subtotal'], 0, ',', '.') }}" readonly>
                            <input type="hidden" name="items[{{ $index }}][subtotal]" value="{{ $item['subtotal'] }}" class="subtotal-hidden">
                        </div>
                        <button type="button" class="hapus-item bg-red-100 text-red-600 px-2 py-1 rounded text-sm hover:bg-red-200"
                                onclick="hapusItem(this)">
                            Hapus Barang
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="tambah-item" class="bg-blue-100 text-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-200">
                + Tambah Barang
            </button>
        </div>

        {{-- Total --}}
        <div>
            <label class="block mb-1">Total Pembayaran</label>
            <input type="text" id="total-pembayaran" class="w-full border rounded px-3 py-2 font-bold" readonly
                   value="Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}">
            <input type="hidden" name="total" id="total-hidden" value="{{ $pengeluaran->total }}">
        </div>

        {{-- Upload Struk --}}
        <div>
            <label class="block mb-1">Upload Struk (Opsional)</label>
            <input type="file" name="bukti_pembayaran" class="w-full border rounded px-3 py-2">
            @if ($pengeluaran->bukti_pembayaran)
                <p class="text-sm mt-1">
                    Struk saat ini: 
                    <a href="{{ asset('storage/'.$pengeluaran->bukti_pembayaran) }}" target="_blank" class="text-indigo-600 underline">
                        Lihat Struk
                    </a>
                </p>
            @endif
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('pengeluarans.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                Kembali
            </a>
        </div>
    </form>
</div>

{{-- Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('items-container');
        const addButton = document.getElementById('tambah-item');
        let counter = {{ count($pengeluaran->daftar_barang) }};

        addButton.addEventListener('click', function() {
            const newItem = document.createElement('div');
            newItem.className = 'item-item space-y-2 mb-4';
            newItem.innerHTML = `
                <div>
                    <label class="block mb-1">Nama Barang</label>
                    <input type="text" name="items[${counter}][nama]" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-1">Jumlah</label>
                    <input type="number" name="items[${counter}][jumlah]" class="w-full border rounded px-3 py-2 jumlah" min="1" oninput="hitungTotal()" required>
                </div>
                <div>
                    <label class="block mb-1">Harga Satuan</label>
                    <input type="number" name="items[${counter}][harga]" class="w-full border rounded px-3 py-2 harga" min="0" oninput="hitungTotal()" required>
                </div>
                <div>
                    <label class="block mb-1">Subtotal</label>
                    <input type="text" class="w-full border rounded px-3 py-2 subtotal" readonly>
                    <input type="hidden" name="items[${counter}][subtotal]" class="subtotal-hidden">
                </div>
                <button type="button" class="hapus-item bg-red-100 text-red-600 px-2 py-1 rounded text-sm hover:bg-red-200"
                        onclick="hapusItem(this)">
                    Hapus Barang
                </button>
            `;
            container.appendChild(newItem);
            counter++;
        });
    });

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function hitungTotal() {
        let total = 0;
        const itemItems = document.querySelectorAll('.item-item');

        itemItems.forEach(item => {
            const jumlah = parseFloat(item.querySelector('.jumlah').value) || 0;
            const harga = parseFloat(item.querySelector('.harga').value) || 0;
            const subtotal = jumlah * harga;

            item.querySelector('.subtotal').value = formatRupiah(subtotal);
            item.querySelector('.subtotal-hidden').value = subtotal;

            total += subtotal;
        });

        document.getElementById('total-pembayaran').value = formatRupiah(total);
        document.getElementById('total-hidden').value = total;
    }

    function hapusItem(button) {
        const item = button.closest('.item-item');
        item.remove();
        hitungTotal();
    }
</script>
@endsection
