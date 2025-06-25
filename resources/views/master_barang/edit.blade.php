@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white shadow-xl rounded-xl p-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">✏️ Edit Barang</h2>

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('master-barang.update', $barang->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="nama_barang" class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang"
                   value="{{ old('nama_barang', $barang->nama_barang) }}"
                   placeholder="Contoh: Bolpoin Hitam"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400">
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('master-barang.index') }}"
               class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                💾 Update
            </button>
        </div>
    </form>
</div>
@endsection
