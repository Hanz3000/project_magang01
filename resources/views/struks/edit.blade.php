@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">✏️ Edit Struk</h2>

    {{-- Error --}}
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-6">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
            <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('struks.update', $struk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Informasi Umum --}}
        <div class="space-y-3">
            <div>
                <label class="block font-medium text-gray-700">Nama Toko</label>
                <input type="text" name="nama_toko" class="w-full p-2 border rounded" value="{{ old('nama_toko', $struk->nama_toko) }}" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Nomor Struk</label>
                <input type="text" name="nomor_struk" class="w-full p-2 border rounded" value="{{ old('nomor_struk', $struk->nomor_struk) }}" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Tanggal Struk</label>
                <input type="date" name="tanggal_struk" class="w-full p-2 border rounded" value="{{ old('tanggal_struk', $struk->tanggal_struk) }}" required>
            </div>
        </div>

        <hr class="my-4">

        {{-- Items --}}
        <div>
            <label class="block font-medium text-gray-700 mb-2">📦 Daftar Barang</label>
            <div id="items-wrapper" class="space-y-3">
                @foreach ($struk->items as $index => $item)
                <div class="flex flex-wrap gap-2 item-row">
                    <input type="text" name="items[{{ $index }}][nama]" placeholder="Nama Barang" class="flex-1 p-2 border rounded" value="{{ $item->nama }}">
                    <input type="number" name="items[{{ $index }}][jumlah]" placeholder="Jumlah" class="p-2 border rounded w-24" value="{{ $item->jumlah }}">
                    <input type="number" name="items[{{ $index }}][harga]" placeholder="Harga" class="p-2 border rounded w-32" value="{{ $item->harga }}">
                    <button type="button" class="remove-item text-red-500 font-bold px-2">×</button>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-item" class="mt-2 text-blue-600 hover:underline text-sm">+ Tambah Item</button>
        </div>

        <hr class="my-4">

        {{-- Total Harga --}}
        <div>
            <label class="block font-medium text-gray-700">💰 Total Harga</label>
            <input type="number" name="total_harga" class="w-full p-2 border rounded" placeholder="Total Harga" value="{{ old('total_harga', $struk->total_harga) }}" required>
        </div>

        {{-- Foto --}}
        <div>
            <label class="block font-medium text-gray-700">🖼️ Foto Struk</label>
            <input type="file" name="foto_struk" class="w-full p-2 border rounded">
            @if($struk->foto_struk)
            <div class="mt-2">
                <p class="text-sm text-gray-600 mb-1">Foto saat ini:</p>
                <img src="{{ asset('storage/' . $struk->foto_struk) }}" width="120" class="rounded border">
            </div>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                💾 Perbarui Struk
            </button>
        </div>
    </form>
</div>

{{-- Tambah item via JS --}}
<script>
    document.getElementById('add-item').addEventListener('click', function() {
        const wrapper = document.getElementById('items-wrapper');
        const index = wrapper.querySelectorAll('.item-row').length;
        const div = document.createElement('div');
        div.className = 'flex flex-wrap gap-2 item-row';
        div.innerHTML = `
            <input type="text" name="items[${index}][nama]" placeholder="Nama Barang" class="flex-1 p-2 border rounded">
            <input type="number" name="items[${index}][jumlah]" placeholder="Jumlah" class="p-2 border rounded w-24">
            <input type="number" name="items[${index}][harga]" placeholder="Harga" class="p-2 border rounded w-32">
            <button type="button" class="remove-item text-red-500 font-bold px-2">×</button>
        `;
        wrapper.appendChild(div);
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });


    function hitungTotalHarga() {
        let total = 0;
        document.querySelectorAll('#items-wrapper .item-row').forEach(row => {
            const jumlah = parseFloat(row.querySelector('[name$="[jumlah]"]')?.value) || 0;
            const harga = parseFloat(row.querySelector('[name$="[harga]"]')?.value) || 0;
            total += jumlah * harga;
        });
        document.querySelector('input[name="total_harga"]').value = total;
    }

    // Trigger hitung total saat input jumlah/harga berubah
    document.addEventListener('input', function(e) {
        if (e.target.name.includes('[jumlah]') || e.target.name.includes('[harga]')) {
            hitungTotalHarga();
        }
    });

    // Hitung total ulang setelah item ditambahkan
    document.getElementById('add-item').addEventListener('click', function() {
        const wrapper = document.getElementById('items-wrapper');
        const index = wrapper.querySelectorAll('.item-row').length;
        const div = document.createElement('div');
        div.className = 'flex flex-wrap gap-2 item-row';
        div.innerHTML = `
            <input type="text" name="items[${index}][nama]" placeholder="Nama Barang" class="flex-1 p-2 border rounded">
            <input type="number" name="items[${index}][jumlah]" placeholder="Jumlah" class="p-2 border rounded w-24">
            <input type="number" name="items[${index}][harga]" placeholder="Harga" class="p-2 border rounded w-32">
            <button type="button" class="remove-item text-red-500 font-bold px-2">×</button>
        `;
        wrapper.appendChild(div);
    });

    // Hitung ulang saat item dihapus
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
            hitungTotalHarga();
        }
    });

    // Jalankan saat halaman dimuat untuk inisialisasi
    window.addEventListener('DOMContentLoaded', () => {
        hitungTotalHarga();
    });
</script>
@endsection