@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">📊 Dashboard</h1>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

        {{-- Total Struk --}}
        <div class="bg-white p-6 rounded-lg shadow border flex items-center gap-4">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                📄
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Struk</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalStruk }}</p>
            </div>
        </div>

        {{-- Tambah Struk --}}
        <a href="{{ route('struks.create') }}" class="bg-white p-6 rounded-lg shadow border flex items-center gap-4 hover:bg-blue-50 transition">
            <div class="bg-green-100 text-green-600 p-3 rounded-full">
                ➕
            </div>
            <div>
                <p class="text-gray-600 text-sm">Tambah Struk</p>
                <p class="text-base font-semibold text-green-700">Klik untuk tambah</p>
            </div>
        </a>

        {{-- Lihat Daftar --}}
        <a href="{{ route('struks.index') }}" class="bg-white p-6 rounded-lg shadow border flex items-center gap-4 hover:bg-blue-50 transition">
            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                📑
            </div>
            <div>
                <p class="text-gray-600 text-sm">Daftar Struk</p>
                <p class="text-base font-semibold text-yellow-700">Lihat semua</p>
            </div>
        </a>
    </div>
</div>
@endsection
