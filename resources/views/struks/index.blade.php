@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8 animate-fadeIn">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3 animate-slideDown">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-800">Data Struk</h3>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('struks.export.excel') }}" class="px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-sm">Excel</a>
                        <a href="{{ route('struks.export.csv') }}" class="px-3 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 text-sm">CSV</a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">No.</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Nama Toko</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Nomor Struk</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Tanggal</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700">Nama Barang</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Jumlah</th>
                            <th class="px-4 py-4 text-right font-semibold text-slate-700">Harga</th>
                            <th class="px-4 py-4 text-right font-semibold text-slate-700">Total</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php $no = 1; @endphp
                        @foreach($struks as $struk)
                        @php $items = json_decode($struk->items, true); @endphp
                        @if (request('edit') == $struk->id)
                        <form method="POST" action="{{ route('struks.updateItems', $struk->id) }}">
                            @csrf
                            @method('PUT')
                            @endif
                            @foreach($items as $index => $item)
                            <tr>
                                @if ($loop->first)
                                <td rowspan="{{ count($items) }}" class="text-center">{{ $no++ }}</td>
                                <td rowspan="{{ count($items) }}">{{ $struk->nama_toko }}</td>
                                <td rowspan="{{ count($items) }}">{{ $struk->nomor_struk }}</td>
                                <td rowspan="{{ count($items) }}">{{ $struk->tanggal_struk }}</td>
                                @endif

                                @if (request('edit') == $struk->id)
                                <input type="hidden" name="item_index[]" value="{{ $index }}">
                                <td><input name="nama[]" value="{{ $item['nama'] }}" class="w-full border rounded px-2 py-1"></td>
                                <td><input name="jumlah[]" type="number" value="{{ $item['jumlah'] }}" class="w-16 border rounded px-2 py-1 text-center"></td>
                                <td><input name="harga[]" type="number" value="{{ $item['harga'] }}" class="w-24 border rounded px-2 py-1 text-right"></td>
                                <td class="text-right font-semibold">
                                    Rp{{ number_format($item['jumlah'] * $item['harga'], 0, ',', '.') }}
                                </td>
                                @if ($loop->first)
                                <td rowspan="{{ count($items) }}">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('struks.show', $struk->id) }}" class="text-green-600 hover:underline text-sm">Detail</a>
                                        <button type="submit" class="text-blue-600 hover:underline text-sm">Simpan Semua</button>
                                        <a href="{{ route('struks.index') }}" class="text-slate-500 hover:underline text-xs">Batal</a>
                                    </div>
                                </td>
                                @endif
                                @else
                                <td>{{ $item['nama'] }}</td>
                                <td class="text-center">{{ $item['jumlah'] }}</td>
                                <td class="text-right">Rp{{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td class="text-right font-semibold">
                                    Rp{{ number_format($item['jumlah'] * $item['harga'], 0, ',', '.') }}
                                </td>
                                @if ($loop->first)
                                <td rowspan="{{ count($items) }}">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('struks.show', $struk->id) }}" class="text-green-600 hover:underline text-sm">Detail</a>
                                        <a href="{{ route('struks.index', ['edit' => $struk->id]) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                        <form action="{{ route('struks.destroy', $struk->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                                @endif
                            </tr>
                            @endforeach
                            @if (request('edit') == $struk->id)
                        </form>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.6s ease-out;
    }
</style>
@endsection