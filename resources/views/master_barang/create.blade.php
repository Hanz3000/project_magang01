@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Tambah Master Barang</h2>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <ul class="list-disc ml-6">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form tambah barang --}}
    <form action="{{ route('master-barang.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="nama_barang" class="block font-semibold mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="w-full border p-2 rounded" value="{{ old('nama_barang') }}" required>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
</div>
@endsection
