@extends('layouts.app')

@section('content')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Font Awesome untuk ikon --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* === FONT CONSISTENCY === */
    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Base font sizes */
    :root {
        --font-xs: 0.75rem;
        /* 12px */
        --font-sm: 0.875rem;
        /* 14px */
        --font-base: 1rem;
        /* 16px */
        --font-lg: 1.125rem;
        /* 18px */
        --font-xl: 1.25rem;
        /* 20px */
        --font-2xl: 1.5rem;
        /* 24px */
    }

    .form-card {
        background: white;
        border-radius: 16px;
        padding: 2px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .form-inner {
        background: white;
        border-radius: 14px;
        padding: 2.5rem;
    }

    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .input-group input,
    .input-group select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: var(--font-base);
        font-weight: 400;
        transition: all 0.2s ease;
        background: #f8fafc;
    }

    .input-group input:focus,
    .input-group select:focus {
        outline: none;
        border-color: #5a67d8;
        background: white;
        box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1);
    }

    .input-group label {
        position: absolute;
        top: -8px;
        left: 12px;
        background: white;
        padding: 0 8px;
        font-size: var(--font-sm);
        font-weight: 600;
        color: #4a5568;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: var(--font-xl);
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #edf2f7;
    }

    .item-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .item-table thead th {
        background: #f7fafc;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        font-size: var(--font-sm);
        color: #4a5568;
        border-bottom: 2px solid #e2e8f0;
    }

    .item-row {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .item-row:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .item-row td {
        padding: 16px;
        vertical-align: middle;
        border-top: 1px solid #edf2f7;
        border-bottom: 1px solid #edf2f7;
        font-size: var(--font-base);
    }

    .item-row td:first-child {
        border-left: 1px solid #edf2f7;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .item-row td:last-child {
        border-right: 1px solid #edf2f7;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: var(--font-sm);
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #5a67d8;
        color: white;
        font-size: var(--font-base);
        padding: 12px 24px;
    }

    .btn-primary:hover {
        background: #4c51bf;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #718096;
        color: white;
    }

    .btn-secondary:hover {
        background: #4a5568;
    }

    .btn-danger {
        background: #e53e3e;
        color: white;
        padding: 8px 12px;
        font-size: var(--font-sm);
        border-radius: 8px;
    }

    .btn-danger:hover {
        background: #c53030;
    }

    .file-upload-container {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .file-upload-container:hover {
        border-color: #5a67d8;
        background: #f8fafc;
    }

    .file-upload-container input {
        display: none;
    }

    .file-upload-container p:first-of-type {
        font-size: var(--font-lg);
        font-weight: 600;
        color: #4a5568;
    }

    .file-upload-container p:last-of-type {
        font-size: var(--font-sm);
        color: #718096;
        margin-top: 0.5rem;
    }

    .file-preview {
        margin-top: 1.5rem;
        display: none;
    }

    .file-preview img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .file-preview .mt-3 span {
        font-size: var(--font-sm);
        color: #4a5568;
    }

    .file-preview .mt-3 button {
        font-size: var(--font-sm);
        color: #e53e3e;
        background: none;
        border: none;
        cursor: pointer;
    }

    .file-preview .mt-3 button:hover {
        color: #c53030;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        border-left: 4px solid;
        font-size: var(--font-base);
    }

    .alert-success {
        background: #f0fff4;
        color: #22543d;
        border-left-color: #48bb78;
    }

    .alert-error {
        background: #fed7d7;
        color: #742a2a;
        border-left-color: #e53e3e;
    }

    .alert ul {
        margin-top: 0.5rem;
        font-size: var(--font-sm);
    }

    .currency-input {
        position: relative;
    }

    .currency-input span {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
        font-size: var(--font-base);
        font-weight: 500;
    }

    .currency-input input {
        padding-left: 30px !important;
    }

    .subtotal-display {
        font-weight: 600;
        font-size: var(--font-base);
        color: #2d3748;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 8px !important;
        height: auto !important;
        background: #f8fafc !important;
        font-size: var(--font-base) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: var(--font-base) !important;
        color: #4a5568 !important;
        font-weight: 400 !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #5a67d8 !important;
        box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1) !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        padding: 8px !important;
        font-size: var(--font-base) !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5a67d8 !important;
    }

    .select2-container--default .select2-results__option {
        padding: 8px 12px;
        position: relative;
        padding-left: 35px;
        font-size: var(--font-base) !important;
    }

    .select2-container--default .select2-results__option:before {
        content: "";
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 3px;
        background: white;
    }

    .select2-container--default .select2-results__option[aria-selected=true]:before,
    .select2-container--default .select2-results__option--highlighted[aria-selected]:before {
        content: "✓";
        color: white;
        background-color: #5a67d8;
        border-color: #5a67d8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--font-xs);
    }

    .select2-container--open .select2-dropdown {
        top: 0;
        bottom: auto;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        border-top: none;
    }

    .total-display {
        font-size: var(--font-2xl);
        font-weight: 700;
        color: #2d3748;
        padding: 1rem;
        background: #f7fafc;
        border-radius: 10px;
        text-align: right;
        margin: 1.5rem 0;
    }

    /* Icon sizes consistency */
    .section-title i {
        font-size: var(--font-lg);
    }

    .btn i {
        font-size: var(--font-sm);
    }

    .file-upload-container i {
        font-size: 2.5rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }

    .alert i {
        font-size: var(--font-base);
    }

    .currency-input i,
    .input-group label i {
        font-size: var(--font-xs);
    }

    @media (max-width: 768px) {
        .form-inner {
            padding: 1.5rem;
        }

        .section-title {
            font-size: var(--font-lg);
        }

        .item-table thead {
            display: none;
        }

        .item-row td {
            display: block;
            text-align: right;
            padding: 12px 16px;
            border: none !important;
            border-bottom: 1px solid #edf2f7 !important;
            font-size: var(--font-base);
        }

        .item-row td:before {
            content: attr(data-label);
            float: left;
            font-weight: 600;
            font-size: var(--font-sm);
            color: #4a5568;
        }

        .item-row td:first-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            padding-top: 16px;
        }

        .item-row td:last-child {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            padding-bottom: 16px;
            border-bottom: none !important;
        }

        .btn {
            width: 100%;
            justify-content: center;
            font-size: var(--font-base);
        }

        .total-display {
            font-size: var(--font-xl);
            text-align: center;
        }

        .file-upload-container p:first-of-type {
            font-size: var(--font-base);
        }

        .file-upload-container p:last-of-type {
            font-size: var(--font-xs);
        }
    }
</style>

<div class="max-w-6xl mx-auto p-4 mt-8">
    <div class="form-card">
        <div class="form-inner">

            {{-- Notifikasi --}}
            @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            @endif

            {{-- Error --}}
            @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('struks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Info Umum --}}
                <div class="section-title">
                    <i class="fas fa-store"></i>
                    Informasi Toko
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="input-group">
                        <label for="nama_toko">
                            <i class="fas fa-store-alt mr-1"></i>
                            Nama Toko
                        </label>
                        <input type="text" id="nama_toko" name="nama_toko" placeholder="Masukkan nama toko"
                            value="{{ old('nama_toko') }}" required>
                    </div>

                    <div class="input-group">
                        <label for="nomor_struk">
                            <i class="fas fa-hashtag mr-1"></i>
                            Nomor Struk
                        </label>
                        <input type="text" id="nomor_struk" name="nomor_struk" placeholder="Masukkan nomor struk"
                            value="{{ old('nomor_struk') }}" required>
                    </div>

                    <div class="input-group">
                        <label for="tanggal_struk">
                            <i class="fas fa-calendar mr-1"></i>
                            Tanggal Struk
                        </label>
                        <input type="date" id="tanggal_struk" name="tanggal_struk"
                            value="{{ old('tanggal_struk', date('Y-m-d')) }}" required>
                    </div>
                </div>

                {{-- Items --}}
                <div class="section-title">
                    <i class="fas fa-shopping-cart"></i>
                    Daftar Barang
                </div>

                <div class="overflow-x-auto mb-6">
                    <table class="item-table">
                        <thead>
                            <tr>
                                <th width="40%">Nama Barang</th>
                                <th width="15%">Jumlah</th>
                                <th width="20%">Harga Satuan</th>
                                <th width="20%">Subtotal</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            <tr class="item-row" data-item="0">
                                <td data-label="Nama Barang">
                                    <div class="input-group">
                                        <select name="items[0][nama]" class="select-barang" required>
                                            <option value="" disabled selected>Pilih Barang</option>
                                            @foreach ($barangList as $barang)
                                            <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td data-label="Jumlah">
                                    <div class="input-group">
                                        <input type="number" name="items[0][jumlah]" placeholder="0" class="jumlah"
                                            min="1" oninput="updateTotal(this)" required>
                                    </div>
                                </td>
                                <td data-label="Harga Satuan">
                                    <div class="input-group currency-input">
                                        <span>Rp</span>
                                        <input type="number" name="items[0][harga]" placeholder="0" class="harga"
                                            min="0" oninput="updateTotal(this)" required>
                                    </div>
                                </td>
                                <td data-label="Subtotal">
                                    <div class="subtotal-display" id="subtotal-0">Rp 0</div>
                                    <input type="hidden" name="items[0][subtotal]" class="subtotal" value="0">
                                </td>
                                <td>
                                    <button type="button" onclick="hapusBarang(this)" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center mb-6">
                    <button type="button" onclick="tambahBarang()" class="btn btn-secondary">
                        <i class="fas fa-plus"></i>
                        Tambah Barang Lain
                    </button>
                </div>

                {{-- Total --}}
                <div class="total-display" style="text-align: left;">
                    Total Harga: <span id="total-display">Rp 0</span>
                    <input type="hidden" name="total_harga" id="total_harga" value="0">
                </div>

                {{-- Upload Foto --}}
                <div class="section-title">
                    <i class="fas fa-camera"></i>
                    Upload Foto Struk
                </div>

                <div class="file-upload-container" onclick="document.getElementById('foto_struk').click()"
                    id="upload-container">
                    <input type="file" id="foto_struk" name="foto_struk" accept="image/*">
                    <div>
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik untuk upload foto struk</p>
                        <p>Maksimal 5MB • JPG, PNG</p>
                    </div>
                </div>

                <div class="file-preview" id="file-preview">
                    <img id="preview-image" src="#" alt="Preview Foto Struk" style="display: none;">
                    <div class="mt-3 flex justify-between items-center">
                        <span id="file-name"></span>
                        <button type="button" onclick="hapusFoto()">
                            <i class="fas fa-times"></i> Hapus
                        </button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-center mt-8">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Struk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        initializeSelect2();

        // File upload preview
        $('#foto_struk').change(function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB');
                    return;
                }

                $('#file-name').text(file.name);
                $('#upload-container').hide();

                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#preview-image').attr('src', event.target.result).show();
                }
                reader.readAsDataURL(file);

                $('#file-preview').show();
            }
        });

        // Format angka saat pertama kali load
        updateGrandTotal();
    });

    function initializeSelect2(element = '.select-barang') {
        $(element).each(function() {
            $(this).select2({
                placeholder: "Cari atau pilih barang...",
                width: '100%',
                dropdownParent: $(this).closest('td'), // Penting: pastikan dropdown di dalam sel TD
                dropdownPosition: 'below',
                language: {
                    noResults: function() {
                        return "Barang tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });
        });
    }

    let itemIndex = 1;
    const barangOptions = @json($barangList);

    function tambahBarang() {
        const container = $('#items-container');

        let selectHtml = `<select name="items[${itemIndex}][nama]" class="select-barang" required>
                            <option value="" disabled selected>Pilih Barang</option>`;
        barangOptions.forEach(item => {
            selectHtml += `<option value="${item.nama_barang}">${item.nama_barang}</option>`;
        });
        selectHtml += `</select>`;

        const newRow = $(`
            <tr class="item-row" data-item="${itemIndex}">
                <td data-label="Nama Barang">
                    <div class="input-group">
                        ${selectHtml}
                    </div>
                </td>
                <td data-label="Jumlah">
                    <div class="input-group">
                        <input type="number" 
                               name="items[${itemIndex}][jumlah]" 
                               placeholder="0"
                               class="jumlah" 
                               min="1"
                               oninput="updateTotal(this)"
                               required>
                    </div>
                </td>
                <td data-label="Harga Satuan">
                    <div class="input-group currency-input">
                        <span>Rp</span>
                        <input type="number" 
                               name="items[${itemIndex}][harga]" 
                               placeholder="0"
                               class="harga" 
                               min="0"
                               oninput="updateTotal(this)"
                               required>
                    </div>
                </td>
                <td data-label="Subtotal">
                    <div class="subtotal-display" id="subtotal-${itemIndex}">Rp 0</div>
                    <input type="hidden" 
                           name="items[${itemIndex}][subtotal]" 
                           class="subtotal" 
                           value="0">
                </td>
                <td>
                    <button type="button" 
                            onclick="hapusBarang(this)" 
                            class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        // Tambahkan dengan efek
        newRow.hide();
        container.append(newRow);
        newRow.fadeIn(300);

        // Inisialisasi Select2 untuk item baru
        initializeSelect2(newRow.find('.select-barang'));

        itemIndex++;
    }

    function hapusBarang(button) {
        const row = $(button).closest('.item-row');
        row.fadeOut(300, function() {
            row.remove();
            updateGrandTotal();
        });
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateTotal(el) {
        const row = $(el).closest('.item-row');
        const itemId = row.data('item');
        const jumlah = parseFloat(row.find('.jumlah').val()) || 0;
        const harga = parseFloat(row.find('.harga').val()) || 0;
        const subtotal = jumlah * harga;

        // Update tampilan subtotal
        $('#subtotal-' + itemId).text(formatRupiah(subtotal));
        row.find('.subtotal').val(subtotal);

        // Animasi perubahan
        $('#subtotal-' + itemId).css({
            'color': '#5a67d8',
            'font-weight': 'bold',
            'transition': 'all 0.3s ease'
        });

        setTimeout(() => {
            $('#subtotal-' + itemId).css({
                'color': '#2d3748',
                'font-weight': '600'
            });
        }, 500);

        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });

        $('#total_harga').val(total);
        $('#total-display').text(formatRupiah(total));

        // Animasi total
        $('#total-display').css({
            'transform': 'scale(1.05)',
            'display': 'inline-block',
            'color': '#5a67d8'
        });

        setTimeout(() => {
            $('#total-display').css({
                'transform': 'scale(1)',
                'color': '#2d3748'
            });
        }, 300);
    }

    function hapusFoto() {
        $('#foto_struk').val('');
        $('#preview-image').attr('src', '#').hide();
        $('#file-name').text('');
        $('#file-preview').hide();
        $('#upload-container').show();
    }

    // Auto-focus ke field berikutnya
    $(document).on('keypress', 'input[type="number"]', function(e) {
        if (e.which === 13) { // Enter key
            const inputs = $('input[type="number"]:visible');
            const index = inputs.index(this);
            if (index < inputs.length - 1) {
                inputs.eq(index + 1).focus();
            }
        }
    });
</script>
@endsection