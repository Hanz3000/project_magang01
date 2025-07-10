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

                {{-- 🔴 Existing Items --}}
                <div class="space-y-3" id="items-container">
                    <div class="grid grid-cols-4 gap-4 font-medium text-gray-700 pb-2 border-b">
                        <div>Nama Barang</div>
                        <div>Jumlah</div>
                        <div>Harga Satuan</div>
                        <div>Aksi</div>
                    </div>

                    @foreach ($pengeluaran->daftar_barang as $index => $item)
                    @php
                    $kodeBarang = $item['kode_barang'] ?? $item['nama']; // Cek apakah ada kode_barang yang eksplisit
                    $barang = \App\Models\Barang::where('kode_barang', $kodeBarang)->first();

                    $namaBarang = $barang->nama_barang ?? ($item['nama'] ?? 'Tidak diketahui');
                    $stokTersedia = $barang?->jumlah ?? 0;

                    @endphp

                    <div class="grid grid-cols-4 gap-4 items-center py-3 border-b existing-item"
                        data-kode="{{ $kodeBarang }}" data-stok="{{ $stokTersedia }}">
                        <div class="text-sm">
                            <div class="text-gray-900 font-semibold">{{ $namaBarang }}</div>
                            <div class="text-gray-500 text-xs">Kode: {{ $kodeBarang }}</div>
                            <div class="text-gray-500 text-xs">
                                Stok: <span class="stok-tersedia" data-awal="{{ $stokTersedia }}">{{ $stokTersedia }}</span>
                            </div>
                        </div>

                        {{-- Input jumlah dan harga --}}
                        <div>
                            <input type="number" name="existing_items[{{ $index }}][jumlah]" value="{{ $item['jumlah'] }}" min="1"
                                class="w-full border rounded px-3 py-2 existing-jumlah-input">
                        </div>
                        <div>
                            <input type="number" name="existing_items[{{ $index }}][harga]" value="{{ $item['harga'] }}" min="0"
                                class="w-full border rounded px-3 py-2 existing-harga-input">
                        </div>
                        <div>
                            <input type="hidden" name="existing_items[{{ $index }}][nama]" value="{{ $item['nama'] }}">
                            <button type="button" class="hapus-item text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <input type="hidden" name="existing_items[{{ $index }}][kode_barang]" value="{{ $kodeBarang }}">

                        </div>
                    </div>
                    @endforeach

                </div>

                {{-- Hidden fields --}}
                <input type="hidden" name="nama_toko" value="{{ $pengeluaran->nama_toko }}">
                <input type="hidden" name="nomor_struk" value="{{ $pengeluaran->nomor_struk }}">

                {{-- 🔴 Tanggal --}}
                <div>
                    <label class="block mb-1 font-medium">Tanggal Pengeluaran</label>
                    <input type="date" name="tanggal" class="w-full border rounded px-3 py-2"
                        value="{{ old('tanggal', \Carbon\Carbon::parse($pengeluaran->tanggal)->format('Y-m-d')) }}" required>

                </div>

                {{-- 🔴 Pegawai --}}
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

                {{-- 🔴 Tambah Item Baru --}}
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-medium">+ Tambah Item Baru</h3>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-3">
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Cari barang atau pilih dari daftar</label>
                            <select class="w-full border rounded px-3 py-2 barang-select">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $barang)
                                <option value="{{ $barang->nama_barang }}"
                                    data-kode="{{ $barang->kode_barang }}"
                                    data-harga="{{ $barang->harga_satuan }}"
                                    data-stok="{{ $barang->jumlah }}">
                                    {{ $barang->kode_barang }} - {{ $barang->nama_barang }} (Stok: {{ $barang->jumlah }})
                                </option>
                                @endforeach
                            </select>

                        </div>

                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Jumlah</label>
                            <input type="number" class="w-full border rounded px-3 py-2 new-jumlah-input" value="1" min="1" max="">

                        </div>

                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Harga Satuan</label>
                            <input type="number" class="w-full border rounded px-3 py-2 new-harga-input" min="0">
                        </div>
                    </div>

                    <button type="button" id="tambah-barang"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        + Tambah
                    </button>
                </div>

                {{-- 🔴 Total --}}
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-lg">Total Semua Item</span>
                        <span id="total-semua" class="font-bold text-xl">Rp {{ number_format($pengeluaran->total, 0, ',', '.') }}</span>
                    </div>
                    <input type="hidden" name="total" id="total-hidden" value="{{ $pengeluaran->total }}">
                </div>

                {{-- 🔴 Action Buttons --}}
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

            {{-- Template item baru --}}
            <template id="template-barang-baru">
                <div class="grid grid-cols-4 gap-4 items-center py-3 border-b barang-baru" data-kode="" data-stok="">
                    <div class="text-sm">
                        <div class="text-gray-900 font-semibold new-nama-barang"></div>
                        <div class="text-gray-500 text-xs">Kode: <span class="new-kode-barang"></span></div>
                        <div class="text-gray-500 text-xs">Stok: <span class="stok-tersedia" data-awal=""></span></div>
                    </div>

                    <div>
                        <input type="number" name="new_items[{{ $index }}][jumlah]" class="w-full border rounded px-3 py-2 new-jumlah-field" min="0">
                    </div>
                    <div>
                        <input type="number" name="new_items[{{ $index }}][harga]" class="w-full border rounded px-3 py-2 new-harga-field" min="0">
                    </div>
                    <div>
                        <input type="hidden" name="new_items[][nama]" class="new-nama-field" value="">
                        <input type="hidden" name="new_items[{{ $index }}][kode_barang]" class="new-kode-field" value="">

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
            const hargaInputBaru = document.querySelector('.new-harga-input');
            const submitBtn = document.getElementById('submit-button');

            // 🔸 Stok sementara semua barang
            const stokSementara = new Map();



            // 🔸 Inisialisasi dari <option>
            barangSelect.querySelectorAll('option').forEach(option => {
                const kode = option.getAttribute('data-kode');
                const stok = parseInt(option.getAttribute('data-stok')) || 0;
                if (kode) stokSementara.set(kode, stok);
            });

            // 🔸 Hitung total
            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.existing-item').forEach(item => {
                    const jumlah = parseInt(item.querySelector('.existing-jumlah-input').value) || 0;
                    const harga = parseInt(item.querySelector('.existing-harga-input').value) || 0;
                    total += jumlah * harga;
                });
                document.querySelectorAll('.barang-baru').forEach(item => {
                    const jumlah = parseInt(item.querySelector('.new-jumlah-field').value) || 0;
                    const harga = parseInt(item.querySelector('.new-harga-field').value) || 0;
                    total += jumlah * harga;
                });

                document.getElementById('total-semua').textContent = 'Rp ' + total.toLocaleString('id-ID');
                document.getElementById('total-hidden').value = total;
            }

            function validateAll() {
                let valid = true;
                document.querySelectorAll('.existing-item').forEach(item => {
                    const input = item.querySelector('.existing-jumlah-input');
                    const jumlah = parseInt(input.value) || 0;
                    const stok = parseInt(item.getAttribute('data-stok')) || 0;

                    if (jumlah > stok) {
                        input.classList.add('border-red-500');
                        valid = false;
                    } else {
                        input.classList.remove('border-red-500');
                    }
                });

                submitBtn.disabled = !valid;
            }

            tambahBtn.addEventListener('click', () => {
                const option = barangSelect.options[barangSelect.selectedIndex];
                if (!option.value) return alert('Pilih barang dulu');

                const nama = option.value;
                const kode = option.getAttribute('data-kode');
                const stok = stokSementara.get(kode);
                const harga = hargaInputBaru.value || option.getAttribute('data-harga');
                const jumlah = parseInt(jumlahInputBaru.value) || 1;

                // ✅ Pengecekan yang lebih akurat - hanya di item baru yang belum disimpan
                const sudahAdaSebagaiBaru = [...document.querySelectorAll('.barang-baru')]
                    .some(el => el.getAttribute('data-kode') === kode);

                if (sudahAdaSebagaiBaru) {
                    alert('Barang ini sudah ditambahkan sebelumnya.');
                    return;
                }

                // Di dalam event listener tambah barang
                if (jumlah > stok) {
                    // Tampilkan peringatan lebih informatif
                    alert(`Stok tidak mencukupi. 
           Jumlah diminta: ${jumlah} 
           Stok tersedia: ${stok}
           Stok awal: ${item.querySelector('.stok-tersedia').getAttribute('data-awal')}`);
                    return;
                }

                stokSementara.set(kode, stok - jumlah);

                const clone = template.content.cloneNode(true);
                const el = clone.querySelector('.barang-baru');
                const newIndex = document.querySelectorAll('.barang-baru').length;

                // 💡 Set data-* attribute dan teks
                el.setAttribute('data-kode', kode);
                el.setAttribute('data-stok', stok - jumlah);

                // Update tampilan
                el.querySelector('.new-nama-barang').textContent = nama;
                el.querySelector('.new-kode-barang').textContent = kode;
                el.querySelector('.stok-tersedia').textContent = stok - jumlah;
                el.querySelector('.stok-tersedia').setAttribute('data-awal', stok - jumlah);

                // Update input fields dengan nama yang benar
                el.querySelector('.new-nama-field').name = `new_items[${newIndex}][nama]`;
                el.querySelector('.new-nama-field').value = nama;
                el.querySelector('.new-kode-field').name = `new_items[${newIndex}][kode_barang]`;
                el.querySelector('.new-kode-field').value = kode;
                el.querySelector('.new-jumlah-field').name = `new_items[${newIndex}][jumlah]`;
                el.querySelector('.new-jumlah-field').value = jumlah;
                el.querySelector('.new-jumlah-field').max = stok;
                el.querySelector('.new-harga-field').name = `new_items[${newIndex}][harga]`;
                el.querySelector('.new-harga-field').value = harga;

                // Tombol hapus
                el.querySelector('.hapus-barang').addEventListener('click', () => {
                    stokSementara.set(kode, stokSementara.get(kode) + jumlah);
                    el.remove();
                    calculateTotal();
                    validateAll();
                });

                container.appendChild(clone);

                // Reset form
                barangSelect.value = '';
                jumlahInputBaru.value = 1;
                hargaInputBaru.value = '';
                calculateTotal();
                validateAll();
            });

            let initialLoad = true;

            // 🔸 Existing item: perubahan jumlah
            // Existing item: perubahan jumlah
            document.querySelectorAll('.existing-jumlah-input').forEach(input => {
                const item = input.closest('.existing-item');
                const kode = item.getAttribute('data-kode');
                const maxStok = parseInt(item.getAttribute('data-stok')) || 0;

                if (!window.stokSementara) window.stokSementara = new Map();
                if (!stokSementara.has(kode)) {
                    stokSementara.set(kode, maxStok);
                }

                const jumlahAwal = parseInt(input.value) || 0;

                // ❗ Jangan duplikat pengurangan stok
                stokSementara.set(kode, stokSementara.get(kode) - jumlahAwal);

                input.setAttribute('data-prev', jumlahAwal);

                input.addEventListener('input', () => {
                    const prev = parseInt(input.getAttribute('data-prev')) || 0;
                    const current = parseInt(input.value) || 0;
                    const stokSaatIni = stokSementara.get(kode) ?? 0;

                    const stokSetelah = stokSaatIni + prev - current;

                    if (stokSetelah < 0) {
                        input.value = prev;
                        input.classList.add('border-red-500');
                        alert('Jumlah melebihi stok tersedia.');
                        return;
                    }

                    stokSementara.set(kode, stokSetelah);
                    input.setAttribute('data-prev', current);
                    input.classList.remove('border-red-500');

                    const stokTampilan = item.querySelector('.stok-tersedia');
                    if (stokTampilan) {
                        stokTampilan.textContent = stokSetelah;
                        stokTampilan.setAttribute('data-awal', stokSetelah);
                    }

                    calculateTotal();
                    validateAll();
                });

                input.dispatchEvent(new Event('input'));
            });


            jumlahInputBaru.addEventListener('input', () => {
                const option = barangSelect.options[barangSelect.selectedIndex];
                if (!option) return;

                const kode = option.getAttribute('data-kode');
                const stok = stokSementara.get(kode) || 0;
                let jumlah = parseInt(jumlahInputBaru.value) || 0;

                // Hindari angka negatif/0
                if (jumlah < 1) jumlah = 1;

                // Validasi melebihi stok
                if (jumlah > stok) {
                    jumlahInputBaru.value = stok;
                    jumlahInputBaru.classList.add('border-red-500');
                } else {
                    jumlahInputBaru.value = jumlah;
                    jumlahInputBaru.classList.remove('border-red-500');
                }
            });

        });
    </script>
    @endpush