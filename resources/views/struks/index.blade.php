@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">

    <h2 class="text-xl font-bold mb-4">Daftar Struk</h2>

    <a href="{{ route('struks.create') }}" class="text-blue-600 underline mb-4 inline-block">+ Tambah Struk</a>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-4 flex gap-3">
        <a href="{{ route('struks.export.excel') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export Excel</a>
        <a href="{{ route('struks.export.csv') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Export CSV</a>
    </div>

    <table class="w-full border text-sm">
        <thead>
            <tr class="bg-gray-200 text-left">
                <th class="p-2 border text-center">No.</th>
                <th class="p-2 border">Nama Toko</th>
                <th class="p-2 border">Nomor Struk</th>
                <th class="p-2 border">Tanggal</th>
                <th class="p-2 border">Nama Barang</th>
                <th class="p-2 border text-center">Jumlah</th>
                <th class="p-2 border text-right">Harga Satuan</th>
                <th class="p-2 border text-right">Total</th>
                <th class="p-2 border text-center">Foto</th>
                <th class="p-2 border text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($struks as $struk)
            @php $items = json_decode($struk->items); @endphp
            @foreach($items as $index => $item)
            <tr class="border-b">
                @if ($loop->first)
                <td class="p-2 border text-center" rowspan="{{ count($items) }}">{{ $no++ }}</td>
                <td class="p-2 border" rowspan="{{ count($items) }}">{{ $struk->nama_toko }}</td>
                <td class="p-2 border" rowspan="{{ count($items) }}">{{ $struk->nomor_struk }}</td>
                <td class="p-2 border" rowspan="{{ count($items) }}">{{ $struk->tanggal_struk }}</td>
                @endif

                <td class="p-2 border">
                    {{ is_object($item) ? $item->nama : $item }}
                </td>
                <td class="p-2 border text-center">
                    {{ is_object($item) ? $item->jumlah : '-' }}
                </td>
                <td class="p-2 border text-right">
                    @if (is_object($item))
                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>

                @if ($loop->first)
                <td class="p-2 border text-right font-semibold" rowspan="{{ count($items) }}">
                    Rp{{ number_format($struk->total_harga, 0, ',', '.') }}
                </td>
                <td class="p-2 border text-center" rowspan="{{ count($items) }}">
                    @if($struk->foto_struk)
                    <img src="{{ asset('storage/' . $struk->foto_struk) }}" width="100" class="mx-auto rounded">
                    @else
                    <span class="text-gray-500 italic">Tidak ada</span>
                    @endif
                </td>
                <td class="p-2 border text-center space-y-1" rowspan="{{ count($items) }}">
                    <a href="{{ route('struks.edit', $struk->id) }}" class="inline-block text-blue-600 hover:underline">Edit</a>
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
@endsection