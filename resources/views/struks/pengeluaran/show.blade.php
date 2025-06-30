@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-4">Detail Pengeluaran</h1>

    <div class="space-y-4">
        <div><strong>Kategori:</strong> {{ $pengeluaran->kategori }}</div>
        <div><strong>Deskripsi:</strong> {{ $pengeluaran->deskripsi }}</div>
        <div><strong>Tanggal:</strong> {{ date('d M Y', strtotime($pengeluaran->tanggal)) }}</div>
        <div><strong>Jumlah:</strong> Rp{{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</div>
        <div>
            <strong>Bukti Pembayaran:</strong>
            @if ($pengeluaran->bukti_pembayaran)
                <a href="{{ asset('storage/bukti_pengeluaran/' . $pengeluaran->bukti_pembayaran) }}" target="_blank" class="text-indigo-600">Lihat Bukti</a>
            @else
                <span class="italic text-gray-500">Tidak ada bukti</span>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('pengeluarans.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
            Kembali
        </a>
    </div>
</div>
@endsection
