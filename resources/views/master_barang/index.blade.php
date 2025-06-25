@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-2xl mt-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 mb-1">📦 Master Barang</h2>
            <p class="text-gray-500 text-sm">Kelola daftar barang yang tersedia</p>
        </div>
        <a href="{{ route('master-barang.create') }}"
           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-lg shadow-md hover:from-blue-600 hover:to-blue-700 transition">
            + Tambah Barang
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse rounded overflow-hidden shadow-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">#</th>
                    <th class="py-3 px-6 text-left">Nama Barang</th>
                    <th class="py-3 px-6 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm divide-y divide-gray-200">
                @forelse ($barangs as $barang)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-6">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6">{{ $barang->nama_barang }}</td>
                        <td class="py-3 px-6">
                            <div class="flex space-x-2">
                                <a href="{{ route('master-barang.edit', $barang->id) }}"
                                   class="inline-block px-3 py-1 bg-yellow-400 text-white text-xs rounded hover:bg-yellow-500 transition">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('master-barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 px-6 text-center text-gray-400 italic">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
