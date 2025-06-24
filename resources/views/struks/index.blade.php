@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">📋 Daftar Struk</h2>

    {{-- Tombol Tambah --}}
    <div class="mb-6">
        <a href="{{ route('struks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Struk</a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabel --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow mb-6">
        <table class="w-full table-auto text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
                <tr>
                    <th class="p-3 border text-center">No</th>
                    <th class="p-3 border">Nama Toko</th>
                    <th class="p-3 border">Nomor Struk</th>
                    <th class="p-3 border">Tanggal</th>
                    <th class="p-3 border">Nama Barang</th>
                    <th class="p-3 border text-center">Jumlah</th>
                    <th class="p-3 border text-right">Harga Satuan</th>
                    <th class="p-3 border text-right">Total</th>
                    <th class="p-3 border text-center">Foto</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($struks as $struk)
                @php $items = json_decode($struk->items); @endphp
                @foreach($items as $index => $item)
                <tr class="border-b hover:bg-gray-50">
                    @if ($loop->first)
                    <td class="p-3 border text-center" rowspan="{{ count($items) }}">{{ $no++ }}</td>
                    <td class="p-3 border" rowspan="{{ count($items) }}">{{ $struk->nama_toko }}</td>
                    <td class="p-3 border" rowspan="{{ count($items) }}">{{ $struk->nomor_struk }}</td>
                    <td class="p-3 border" rowspan="{{ count($items) }}">{{ $struk->tanggal_struk }}</td>
                    @endif

                    <td class="p-3 border">
                        {{ is_object($item) ? $item->nama : $item }}
                    </td>
                    <td class="p-3 border text-center">
                        {{ is_object($item) ? $item->jumlah : '-' }}
                    </td>
                    <td class="p-3 border text-right">
                        @if (is_object($item))
                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>

                    @if ($loop->first)
                    <td class="p-3 border text-right font-semibold" rowspan="{{ count($items) }}">
                        Rp{{ number_format($struk->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="p-3 border text-center" rowspan="{{ count($items) }}">
                        @if($struk->foto_struk)
                        <img src="{{ asset('storage/' . $struk->foto_struk) }}" class="w-24 h-auto mx-auto rounded border" alt="Foto Struk">
                        @else
                        <span class="inline-block bg-gray-200 text-gray-600 px-2 py-1 text-xs rounded-full">Tidak ada</span>
                        @endif
                    </td>
                    <td class="p-3 border text-center space-y-1" rowspan="{{ count($items) }}">
                        <a href="{{ route('struks.show', $struk->id) }}" class="block text-green-600 hover:underline">Lihat Detail</a>
                        <a href="{{ route('struks.edit', $struk->id) }}" class="block text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('struks.destroy', $struk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus struk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                    @endif
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tombol Export Pindah ke Bawah --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('struks.export.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export Excel</a>
        <a href="{{ route('struks.export.csv') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Export CSV</a>
    </div>
</div>
@endsection
