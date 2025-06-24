@extends('layouts.app')

@section('content')
<<<<<<< HEAD
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
=======
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8 animate-fadeIn">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8 transform transition-all duration-300 hover:shadow-md">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800">Daftar Struk</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('struks.create') }}"
                        class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Struk
                    </a>
                </div>
            </div>

            {{-- Export Actions integrated into header --}}
            <div class="border-t border-slate-200 pt-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-1.5 bg-green-100 rounded-lg">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700">Export Data:</span>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('struks.export.excel') }}"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md hover:scale-105 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('struks.export.csv') }}"
                        class="inline-flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-all duration-200 shadow-sm hover:shadow-md hover:scale-105 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>
        </div>

        {{-- Success Notification --}}
        @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3 animate-slideDown">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-slate-100 rounded-lg">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800">Data Struk</h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700 border-r border-slate-200">No.</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700 border-r border-slate-200">Nama Toko</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700 border-r border-slate-200">Nomor Struk</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700 border-r border-slate-200">Tanggal</th>
                            <th class="px-4 py-4 text-left font-semibold text-slate-700 border-r border-slate-200">Nama Barang</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700 border-r border-slate-200">Jumlah</th>
                            <th class="px-4 py-4 text-right font-semibold text-slate-700 border-r border-slate-200">Harga Satuan</th>
                            <th class="px-4 py-4 text-right font-semibold text-slate-700 border-r border-slate-200">Total</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700 border-r border-slate-200">Foto</th>
                            <th class="px-4 py-4 text-center font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php $no = 1; @endphp
                        @foreach($struks as $struk)
                        @php $items = json_decode($struk->items); @endphp
                        @foreach($items as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors duration-150 {{ $loop->parent->index % 2 == 0 ? 'bg-white' : 'bg-slate-25' }}">
                            @if ($loop->first)
                            <td class="px-4 py-4 text-center font-medium text-slate-900 border-r border-slate-200" rowspan="{{ count($items) }}">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
                                    <span class="text-blue-700 font-semibold">{{ $no++ }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 border-r border-slate-200" rowspan="{{ count($items) }}">
                                <div class="font-medium text-slate-900">{{ $struk->nama_toko }}</div>
                            </td>
                            <td class="px-4 py-4 border-r border-slate-200" rowspan="{{ count($items) }}">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $struk->nomor_struk }}
                                </span>
                            </td>
                            <td class="px-4 py-4 border-r border-slate-200" rowspan="{{ count($items) }}">
                                <div class="text-slate-900">{{ $struk->tanggal_struk }}</div>
                            </td>
                            @endif

                            <td class="px-4 py-4 border-r border-slate-200">
                                <div class="font-medium text-slate-900">
                                    {{ is_object($item) ? $item->nama : $item }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center border-r border-slate-200">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ is_object($item) ? $item->jumlah : '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right border-r border-slate-200">
                                <div class="font-medium text-slate-900">
                                    @if (is_object($item))
                                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>

                            @if ($loop->first)
                            <td class="px-4 py-4 text-right font-bold text-green-600 border-r border-slate-200" rowspan="{{ count($items) }}">
                                <div class="text-lg">Rp{{ number_format($struk->total_harga, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-4 py-4 text-center border-r border-slate-200" rowspan="{{ count($items) }}">
                                @if($struk->foto_struk)
                                <div x-data="{ open: false }">
                                    <img @click="open = true" src="{{ asset('storage/' . $struk->foto_struk) }}"
                                        class="w-20 h-20 object-cover rounded-lg shadow-sm border border-slate-200 hover:scale-110 transition-transform duration-200 cursor-pointer">

                                    <!-- Modal -->
                                    <div x-show="open" @click.away="open = false"
                                        class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0">
                                        <div class="relative max-w-3xl w-full p-4">
                                            <img src="{{ asset('storage/' . $struk->foto_struk) }}"
                                                class="w-full h-auto rounded-xl shadow-2xl border border-white">
                                            <button @click="open = false"
                                                class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full p-1 hover:bg-opacity-80">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="flex items-center justify-center">
                                    <div class="w-20 h-20 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-center" rowspan="{{ count($items) }}">
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('struks.show', $struk->id) }}"
                                        class="inline-flex items-center justify-center gap-1 text-xs bg-green-100 text-green-700 px-3 py-2 rounded-lg hover:bg-green-200 transition-colors duration-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('struks.edit', $struk->id) }}"
                                        class="inline-flex items-center justify-center gap-1 text-xs bg-blue-100 text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('struks.destroy', $struk->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus struk ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-1 text-xs bg-red-100 text-red-700 px-3 py-2 rounded-lg hover:bg-red-200 transition-colors duration-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-8 flex justify-start">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 px-6 py-3 rounded-lg hover:bg-slate-200 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
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

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.6s ease-out;
    }

    .animate-slideDown {
        animation: slideDown 0.4s ease-out;
    }

    .bg-slate-25 {
        background-color: #fefefe;
    }
</style>
@endsection
>>>>>>> a19649668aa87c9671b2d99401aca7bbf1836054
