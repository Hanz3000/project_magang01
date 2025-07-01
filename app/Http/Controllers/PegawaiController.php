<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Pegawai::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%$q%")
                    ->orWhere('nip', 'like', "%$q%");
            });
        }

        $pegawais = $query->get();

        return view('pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        // Validasi input sebelum simpan
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|numeric|digits_between:5,20|unique:pegawais,nip',
        ]);

        // Simpan data ke database
        Pegawai::create($request->only('nama', 'nip'));

        // Jika klik "Tambah dan Lanjut"
        if ($request->action === 'save_and_continue') {
            return redirect()
                ->route('pegawai.create')
                ->with('success', 'Data pegawai berhasil ditambahkan. Silakan tambah data baru.');
        }

        // Redirect ke halaman index dengan pesan sukses (untuk tombol Simpan biasa)
        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        // Validasi input saat update, abaikan unique jika NIP tidak diubah
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|numeric|digits_between:5,20|unique:pegawais,nip,' . $pegawai->id,
        ]);

        // Update data
        $pegawai->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
        ]);

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Pegawai berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);
        if (!empty($ids)) {
            \App\Models\Pegawai::whereIn('id', $ids)->delete();
            return redirect()
                ->route('pegawai.index')
                ->with('success', 'Pegawai terpilih berhasil dihapus.');
        }
        return redirect()
            ->route('pegawai.index')
            ->with('success', 'Tidak ada pegawai yang dipilih.');
    }
}
