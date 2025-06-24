@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">

    <h2 class="text-xl font-bold mb-4">Tambah Struk</h2>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tampilkan Error Validasi --}}
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
            <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('struks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <input type="text" name="nama_toko" placeholder="Nama Toko"
            class="w-full p-2 border rounded" value="{{ old('nama_toko') }}" required>

        <input type="text" name="nomor_struk" placeholder="Nomor Struk"
            class="w-full p-2 border rounded" value="{{ old('nomor_struk') }}" required>

        <input type="date" name="tanggal_struk"
            class="w-full p-2 border rounded" value="{{ old('tanggal_struk') }}" required>

        {{-- Dynamic Items --}}
        <div id="items-container">
            <div class="item-row flex space-x-2 mb-2">
                <input type="text" name="items[0][nama]" placeholder="Nama Barang" class="p-2 border rounded w-1/3">
                <input type="number" name="items[0][jumlah]" placeholder="Jumlah" class="p-2 border rounded w-1/4 jumlah" oninput="updateTotal(this)">
                <input type="number" name="items[0][harga]" placeholder="Harga Satuan" class="p-2 border rounded w-1/4 harga" oninput="updateTotal(this)">
                <input type="number" readonly placeholder="Subtotal" class="p-2 border rounded w-1/4 subtotal bg-gray-100">
            </div>
        </div>

        <button type="button" onclick="tambahBarang()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">
            + Tambah Barang
        </button>

        {{-- Total Harga --}}
        <div class="mt-4">
            <label for="total_harga" class="block font-semibold mb-1">Total Harga</label>
            <input type="number" name="total_harga" id="total_harga" readonly
                class="w-full p-2 border rounded bg-gray-100">
        </div>

        {{-- Upload --}}
        <input type="file" name="foto_struk" class="w-full p-2 border rounded">

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
</div>

<script>
    let itemIndex = 1;

    function tambahBarang() {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.className = 'item-row flex space-x-2 mb-2';
        row.innerHTML = `
        <input type="text" name="items[${itemIndex}][nama]" placeholder="Nama Barang" class="p-2 border rounded w-1/3">
        <input type="number" name="items[${itemIndex}][jumlah]" placeholder="Jumlah" class="p-2 border rounded w-1/4 jumlah" oninput="updateTotal(this)">
        <input type="number" name="items[${itemIndex}][harga]" placeholder="Harga Satuan" class="p-2 border rounded w-1/4 harga" oninput="updateTotal(this)">
        <input type="number" readonly placeholder="Subtotal" class="p-2 border rounded w-1/4 subtotal bg-gray-100">
    `;
        container.appendChild(row);
        itemIndex++;
    }

    function updateTotal(el) {
        const row = el.closest('.item-row');
        const jumlah = parseFloat(row.querySelector('.jumlah').value) || 0;
        const harga = parseFloat(row.querySelector('.harga').value) || 0;
        const subtotal = jumlah * harga;
        row.querySelector('.subtotal').value = subtotal;

        // Update total_harga
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total_harga').value = total;
    }
</script>
@endsection