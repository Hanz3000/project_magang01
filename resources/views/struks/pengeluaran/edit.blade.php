@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Edit Pengeluaran</h1>

    <form action="{{ route('pengeluarans.update', $pengeluaran->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Informasi Header -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Nama Toko</label>
                <input type="text" name="nama_toko" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('nama_toko', $pengeluaran->nama_toko) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Nomor Struk</label>
                <input type="text" name="nomor_struk" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('nomor_struk', $pengeluaran->nomor_struk) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Tanggal</label>
                <input type="date" name="tanggal" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
            </div>
        </div>

        <!-- Daftar Barang -->
        <div class="border rounded-lg p-4">
            <h3 class="font-bold text-lg mb-4">Daftar Barang</h3>
            
            <div id="barang-container">
                @foreach($pengeluaran->items as $index => $item)
                <div class="barang-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Nama Barang</label>
                        <input type="text" name="items[{{$index}}][nama]" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ $item->nama_barang }}" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Jumlah</label>
                        <input type="number" name="items[{{$index}}][jumlah]" class="w-full border border-gray-300 rounded-lg px-3 py-2 jumlah" min="1" value="{{ $item->jumlah }}" oninput="hitungTotal()" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Harga Satuan</label>
                        <input type="number" name="items[{{$index}}][harga]" class="w-full border border-gray-300 rounded-lg px-3 py-2 harga" min="0" value="{{ $item->harga_satuan }}" oninput="hitungTotal()" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Subtotal</label>
                        <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 subtotal" value="Rp {{ number_format($item->subtotal, 0, ',', '.') }}" readonly>
                        <input type="hidden" name="items[{{$index}}][subtotal]" class="subtotal-hidden" value="{{ $item->subtotal }}">
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" id="tambah-barang" class="mt-4 bg-blue-100 text-blue-600 px-4 py-2 rounded-lg text-sm hover:bg-blue-200">
                + Tambah Barang
            </button>
        </div>

        <!-- Total Pembayaran -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-700">Total Pembayaran</span>
                <span id="total-pembayaran" class="font-bold text-xl">Rp {{ number_format($pengeluaran->total_harga, 0, ',', '.') }}</span>
                <input type="hidden" name="total_harga" id="total-hidden" value="{{ $pengeluaran->total_harga }}">
            </div>
        </div>

        <!-- Upload Struk -->
        <div>
            <label class="block text-gray-700 font-medium mb-2">Upload Struk (Opsional)</label>
            <input type="file" name="foto_struk" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            
            @if($pengeluaran->foto_struk)
            <div class="mt-2 flex items-center">
                <span class="text-sm text-gray-600 mr-2">Struk saat ini:</span>
                <a href="{{ asset('storage/pengeluaran_struk/' . $pengeluaran->foto_struk) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">Lihat Struk</a>
                <button type="button" onclick="hapusStruk()" class="ml-4 text-red-600 hover:text-red-800 text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
                <input type="hidden" name="hapus_struk" id="hapus-struk" value="0">
            </div>
            @endif
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('pengeluarans.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-200">
                Kembali
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition duration-200">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi counter untuk item baru
        let counter = {{ count($pengeluaran->items) }};
        
        // Tambah barang baru
        document.getElementById('tambah-barang').addEventListener('click', function() {
            const container = document.getElementById('barang-container');
            const newItem = document.createElement('div');
            newItem.className = 'barang-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4';
            newItem.innerHTML = `
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Nama Barang</label>
                    <input type="text" name="items[${counter}][nama]" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Jumlah</label>
                    <input type="number" name="items[${counter}][jumlah]" class="w-full border border-gray-300 rounded-lg px-3 py-2 jumlah" min="1" oninput="hitungTotal()" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Harga Satuan</label>
                    <input type="number" name="items[${counter}][harga]" class="w-full border border-gray-300 rounded-lg px-3 py-2 harga" min="0" oninput="hitungTotal()" required>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Subtotal</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 subtotal" value="Rp 0" readonly>
                    <input type="hidden" name="items[${counter}][subtotal]" class="subtotal-hidden" value="0">
                </div>
            `;
            container.appendChild(newItem);
            counter++;
        });
        
        // Hitung total awal
        hitungTotal();
    });

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function hitungTotal() {
        let total = 0;
        const items = document.querySelectorAll('.barang-item');
        
        items.forEach(item => {
            const jumlah = parseFloat(item.querySelector('.jumlah').value) || 0;
            const harga = parseFloat(item.querySelector('.harga').value) || 0;
            const subtotal = jumlah * harga;
            
            // Update tampilan subtotal
            item.querySelector('.subtotal').value = formatRupiah(subtotal);
            item.querySelector('.subtotal-hidden').value = subtotal;
            
            total += subtotal;
        });
        
        // Update total pembayaran
        document.getElementById('total-pembayaran').textContent = formatRupiah(total);
        document.getElementById('total-hidden').value = total;
    }

    function hapusStruk() {
        if(confirm('Apakah Anda yakin ingin menghapus struk ini?')) {
            document.getElementById('hapus-struk').value = '1';
            document.querySelector('input[name="foto_struk"]').value = '';
            document.querySelector('a[target="_blank"]').parentElement.style.display = 'none';
        }
    }
</script>
@endsection