@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Detail Struk</h2>

    <div class="mb-4">
        <strong>Nama Toko:</strong> {{ $struk->nama_toko }} <br>
        <strong>Nomor Struk:</strong> {{ $struk->nomor_struk }} <br>
        <strong>Tanggal:</strong> {{ $struk->tanggal_struk }} <br>
        <strong>Total Harga:</strong> Rp{{ number_format($struk->total_harga, 0, ',', '.') }} <br>
    </div>

    <div class="mb-4">
        <h3 class="font-semibold mb-2">Daftar Barang:</h3>
        <table class="w-full border text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2 text-center">Jumlah</th>
                    <th class="border p-2 text-right">Harga</th>
                    <th class="border p-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($struk->items as $item)
                <tr>
                    <td class="border p-2">{{ $item->nama }}</td>
                    <td class="border p-2 text-center">{{ $item->jumlah }}</td>
                    <td class="border p-2 text-right">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="border p-2 text-right">
                        Rp{{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($struk->foto_struk)
    <div class="mt-4">
        <h3 class="font-semibold mb-2">Foto Struk:</h3>
        <img src="{{ asset('storage/' . $struk->foto_struk) }}" class="rounded w-64 border">
    </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('struks.index') }}" class="text-blue-600 underline">← Kembali ke daftar</a>
    </div>
</div>
@endsection