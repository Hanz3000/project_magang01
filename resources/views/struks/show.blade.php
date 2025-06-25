@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">🧾 Detail Struk</h2>

    {{-- Info Utama + Foto Struk --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 mb-8 border-b pb-6">
        <div class="flex-1 space-y-3">
            <div><span class="font-semibold text-gray-700">🏪 Nama Toko:</span> {{ $struk->nama_toko }}</div>
            <div><span class="font-semibold text-gray-700">📄 Nomor Struk:</span> {{ $struk->nomor_struk }}</div>
            <div><span class="font-semibold text-gray-700">📅 Tanggal:</span> {{ $struk->tanggal_struk }}</div>
            <div class="text-lg font-semibold text-gray-800">
                💰 Total Harga: Rp{{ number_format($struk->total_harga, 0, ',', '.') }}
            </div>
        </div>

        @if ($struk->foto_struk)
        <div class="w-full md:w-60">
            <h3 class="font-semibold mb-2 text-gray-700">🖼️ Foto Struk:</h3>
            <img src="{{ asset('storage/' . $struk->foto_struk) }}" class="rounded border w-full shadow">
        </div>
        @endif
    </div>

    {{-- Tabel Barang --}}
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-3 text-gray-800">📦 Daftar Barang</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-300 rounded">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border p-3">Nama</th>
                        <th class="border p-3 text-center">Jumlah</th>
                        <th class="border p-3 text-right">Harga</th>
                        <th class="border p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($struk->items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-3">{{ $item->nama }}</td>
                        <td class="border p-3 text-center">{{ $item->jumlah }}</td>
                        <td class="border p-3 text-right">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="border p-3 text-right">
                            Rp{{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('struks.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ← Kembali ke Daftar Struk
        </a>
    </div>
</div>
@endsection