@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">✏️ Edit Data Pegawai</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pegawai.update', $pegawai->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Nama Pegawai --}}
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pegawai</label>
            <input type="text" name="nama" id="nama"
                value="{{ old('nama', $pegawai->nama) }}"
                class="mt-1 block w-full p-2 border rounded shadow-sm focus:ring focus:border-blue-300"
                required>
        </div>

        {{-- NIP --}}
        <div>
            <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
            <input type="number" name="nip" id="nip"
                value="{{ old('nip', $pegawai->nip) }}"
                class="mt-1 block w-full p-2 border rounded shadow-sm focus:ring focus:border-blue-300"
                required>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('pegawai.index') }}"
               class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                Batal
            </a>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                💾 Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
