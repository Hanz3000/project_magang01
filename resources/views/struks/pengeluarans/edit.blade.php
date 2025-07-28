@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20">

        <div class="bg-gradient-to-r from-indigo-600 to-blue-500 p-6 text-white">
            <h2 class="text-xl font-bold">Edit Item Struk</h2>
            <p class="text-sm opacity-80">Ubah, tambah, atau hapus item dalam struk</p>
        </div>

        <form action="{{ route('pengeluarans.update', $pengeluaran->id) }}" method="POST" class="p-6 space-y-4" id="pengeluaran-form">
            @csrf
            @method('PUT')

            <input type="hidden" name="nama_toko" value="{{ $pengeluaran->nama_toko }}">
            <input type="hidden" name="nomor_struk" value="{{ $pengeluaran->nomor_struk }}">

            <div>
                <label class="block mb-1 font-medium">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal" class="w-full border rounded px-3 py-2"
                    value="{{ old('tanggal', \Carbon\Carbon::parse($pengeluaran->tanggal)->format('Y-m-d')) }}" required>
            </div>

            <div>
                <label class="block mb-1 font-medium">Pegawai</label>
                <select name="pegawai_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach ($pegawais as $pegawai)
                    <option value="{{ $pegawai->id }}" {{ $pegawai->id == $pengeluaran->pegawai_id ? 'selected' : '' }}>
                        {{ $pegawai->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-3" id="items-container">
                <div class="grid grid-cols-3 gap-4 font-medium text-gray-700 pb-2 border-b">
                    <div>Nama Barang</div>
                    <div>Jumlah</div>
                    <div>Aksi</div>
                </div>

                @foreach ($pengeluaran->daftar_barang as $index => $item)
                @php
                $kodeBarang = $item['kode_barang'] ?? $item['nama'];
                $barang = \App\Models\Barang::where('kode_barang', $kodeBarang)->first();
                $namaBarang = $barang->nama_barang ?? ($item['nama'] ?? 'Tidak diketahui');
                $stokTersedia = $barang?->jumlah ?? 0;
                @endphp
                <div class="grid grid-cols-3 gap-4 items-center py-3 border-b existing-item"
                    data-kode="{{ $kodeBarang }}" data-stok="{{ $stokTersedia }}">
                    <div class="text-sm">
                        <div class="text-gray-900 font-semibold">{{ $namaBarang }}</div>
                        <div class="text-gray-500 text-xs">Kode: {{ $kodeBarang }}</div>
                        <div class="text-gray-500 text-xs">
                            Stok: <span class="stok-tersedia" data-awal="{{ $stokTersedia }}">{{ $stokTersedia }}</span>
                        </div>
                    </div>
                    <div>
                        <input type="number" name="existing_items[{{ $index }}][jumlah]" value="{{ $item['jumlah'] }}" min="1"
                            class="w-full border rounded px-3 py-2 existing-jumlah-input">
                        <input type="hidden" name="existing_items[{{ $index }}][kode_barang]" value="{{ $kodeBarang }}">
                    </div>
                    <div>
                        <button type="button" class="hapus-item text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-medium">+ Tambah Item Baru</h3>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-3">
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Cari barang atau pilih dari daftar</label>
                        <select class="w-full border rounded px-3 py-2 barang-select">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $barang)
                            <option value="{{ $barang->nama_barang }}"
                                data-kode="{{ $barang->kode_barang }}"
                                data-stok="{{ $barang->jumlah }}">
                                {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->jumlah }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-1">Jumlah</label>
                        <input type="number" class="w-full border rounded px-3 py-2 new-jumlah-input" value="1" min="1">
                    </div>
                </div>

                <button type="button" id="tambah-barang"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    + Tambah
                </button>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('pengeluarans.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" id="submit-button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <template id="template-barang-baru">
            <div class="grid grid-cols-3 gap-4 items-center py-3 border-b barang-baru" data-kode="" data-stok="">
                <div class="text-sm">
                    <div class="text-gray-900 font-semibold new-nama-barang"></div>
                    <div class="text-gray-500 text-xs">Kode: <span class="new-kode-barang"></span></div>
                    <div class="text-gray-500 text-xs">Stok: <span class="stok-tersedia" data-awal=""></span></div>
                </div>
                <div>
                    <input type="number" name="new_items[][jumlah]" class="w-full border rounded px-3 py-2 new-jumlah-field" min="1">
                </div>
                <div>
                    <input type="hidden" name="new_items[][kode_barang]" class="new-kode-field" value="">
                    <button type="button" class="hapus-barang text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const template = document.getElementById('template-barang-baru');
        const container = document.getElementById('items-container');
        const tambahBtn = document.getElementById('tambah-barang');
        const barangSelect = document.querySelector('.barang-select');
        const jumlahInputBaru = document.querySelector('.new-jumlah-input');
        const submitBtn = document.getElementById('submit-button');

<<<<<<< HEAD
=======
        let newItemIndex = 0; // 🆕 Counter untuk new_items

>>>>>>> 13e404229dad74e456a6a75e5e38e7ec08d9399e
        const stokSementara = new Map();

        barangSelect.querySelectorAll('option').forEach(option => {
            const kode = option.getAttribute('data-kode');
            const stok = parseInt(option.getAttribute('data-stok')) || 0;
            if (kode) stokSementara.set(kode, stok);
        });

        tambahBtn.addEventListener('click', () => {
            const option = barangSelect.options[barangSelect.selectedIndex];
            if (!option.value) {
                alert('Pilih barang terlebih dahulu');
                return;
            }

            const nama = option.value;
            const kode = option.getAttribute('data-kode');
            const stok = stokSementara.get(kode);
            const jumlah = parseInt(jumlahInputBaru.value) || 1;

            if (jumlah > stok) {
                alert(`Stok tidak mencukupi!\nStok tersedia: ${stok}\nJumlah diminta: ${jumlah}`);
                return;
            }

            const sudahAda = [...document.querySelectorAll('.existing-item, .barang-baru')]
                .some(el => el.getAttribute('data-kode') === kode);
            
            if (sudahAda) {
                alert('Barang ini sudah ditambahkan sebelumnya.');
                return;
            }

            stokSementara.set(kode, stok - jumlah);

            const clone = template.content.cloneNode(true);
            const el = clone.querySelector('.barang-baru');
<<<<<<< HEAD

=======
            
>>>>>>> 13e404229dad74e456a6a75e5e38e7ec08d9399e
            el.setAttribute('data-kode', kode);
            el.setAttribute('data-stok', stok - jumlah);

            el.querySelector('.new-nama-barang').textContent = nama;
            el.querySelector('.new-kode-barang').textContent = kode;
            el.querySelector('.stok-tersedia').textContent = stok - jumlah;
            el.querySelector('.stok-tersedia').setAttribute('data-awal', stok - jumlah);

<<<<<<< HEAD
            el.querySelector('.new-jumlah-field').value = jumlah;
            el.querySelector('.new-jumlah-field').max = stok - jumlah;
            el.querySelector('.new-kode-field').value = kode;
=======
            // 🆕 Ganti name pakai index
            const jumlahInput = el.querySelector('.new-jumlah-field');
            const kodeInput = el.querySelector('.new-kode-field');
            jumlahInput.value = jumlah;
            jumlahInput.max = stok - jumlah;
            jumlahInput.setAttribute('name', `new_items[${newItemIndex}][jumlah]`);
            kodeInput.value = kode;
            kodeInput.setAttribute('name', `new_items[${newItemIndex}][kode_barang]`);
            newItemIndex++;
>>>>>>> 13e404229dad74e456a6a75e5e38e7ec08d9399e

            el.querySelector('.hapus-barang').addEventListener('click', function() {
                stokSementara.set(kode, stokSementara.get(kode) + jumlah);
                el.remove();
            });

<<<<<<< HEAD
            container.appendChild(el);
=======
            container.appendChild(clone);
>>>>>>> 13e404229dad74e456a6a75e5e38e7ec08d9399e

            barangSelect.value = '';
            jumlahInputBaru.value = 1;
        });

        document.querySelectorAll('.existing-jumlah-input').forEach(input => {
            const item = input.closest('.existing-item');
            const kode = item.getAttribute('data-kode');
            const stokDisplay = item.querySelector('.stok-tersedia');
            const stokAwal = parseInt(stokDisplay.getAttribute('data-awal')) || 0;
            const jumlahAwal = parseInt(input.value) || 0;

            input.addEventListener('input', () => {
                const jumlahBaru = parseInt(input.value) || 0;
                const sisa = stokAwal - (jumlahBaru - jumlahAwal);

                if (sisa < 0) {
                    alert(`Stok tidak cukup!\nStok tersedia: ${stokAwal}\nDiminta: ${jumlahBaru}`);
                    input.value = jumlahAwal;
                    input.classList.add('border-red-500');
                    return;
                }

                input.classList.remove('border-red-500');
                stokDisplay.textContent = sisa;
            });
        });

        document.querySelectorAll('.hapus-item').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.existing-item').remove();
            });
        });

        submitBtn.addEventListener('click', (e) => {
            const newItems = document.querySelectorAll('.barang-baru');
            newItems.forEach((item, index) => {
                const kodeField = item.querySelector('.new-kode-field');
                const jumlahField = item.querySelector('.new-jumlah-field');
                kodeField.name = `new_items[${index}][kode_barang]`;
                jumlahField.name = `new_items[${index}][jumlah]`;
            });
        });
    });
</script>
@endpush