@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
        Tambah Pegawai
    </h2>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- Pesan Validasi --}}
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded mb-6">
        <p class="font-medium">Terjadi kesalahan:</p>
        <ul class="list-disc list-inside text-sm mt-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Pegawai</label>
            <input type="text" name="nama" id="nama"
                value="{{ old('nama') }}"
                placeholder="Contoh: John Doe"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                required>
        </div>

        <div>
            <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
            <input type="text" name="nip" id="nip"
                value="{{ old('nip') }}"
                placeholder="Contoh: 12345678"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                required>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('pegawai.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Batal
            </a>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection