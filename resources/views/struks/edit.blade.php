@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Edit Struk</h2>

    {{-- Error --}}
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
            <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('struks.update', $struk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <input type="text" name="nama_toko" class="w-full p-2 border rounded" value="{{ old('nama_toko', $struk->nama_toko) }}" required>
        <input type="text" name="nomor_struk" class="w-full p-2 border rounded" value="{{ old('nomor_struk', $struk->nomor_struk) }}" required>
        <input type="date" name="tanggal_struk" class="w-full p-2 border rounded" value="{{ old('tanggal_struk', $struk->tanggal_struk) }}" required>

        {{-- Items --}}
        <div id="items-wrapper" class="space-y-2">
            @foreach ($struk->items as $index => $item)
            <div class="flex gap-2 item-row">
                <input type="text" name="items[{{ $index }}][nama]" placeholder="Nama Barang" class="w-full p-2 border rounded" value="{{ $item->nama }}">
                <input type="number" name="items[{{ $index }}][jumlah]" placeholder="Jumlah" class="p-2 border rounded w-24" value="{{ $item->jumlah }}">
                <input type="number" name="items[{{ $index }}][harga]" placeholder="Harga" class="p-2 border rounded w-32" value="{{ $item->harga }}">
                <button type="button" class="remove-item text-red-500 font-bold px-2">×</button>
            </div>
            @endforeach
        </div>

        <button type="button" id="add-item" class="text-blue-600 hover:underline text-sm">+ Tambah Item</button>

        <input type="number" name="total_harga" class="w-full p-2 border rounded" placeholder="Total Harga" value="{{ old('total_harga', $struk->total_harga) }}" required>

        {{-- Foto --}}
        <input type="file" name="foto_struk" class="w-full p-2 border rounded">
        @if($struk->foto_struk)
        <p class="text-sm text-gray-500">Foto saat ini:</p>
        <img src="{{ asset('storage/' . $struk->foto_struk) }}" width="100" class="mb-2">
        @endif

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Perbarui
        </button>
    </form>
</div>

{{-- Tambah item via JS --}}
<script>
    document.getElementById('add-item').addEventListener('click', function() {
        const wrapper = document.getElementById('items-wrapper');
        const index = wrapper.querySelectorAll('.item-row').length;
        const div = document.createElement('div');
        div.className = 'flex gap-2 item-row mb-2';
        div.innerHTML = `
            <input type="text" name="items[${index}][nama]" placeholder="Nama Barang" class="w-full p-2 border rounded">
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
</script>
@endsection