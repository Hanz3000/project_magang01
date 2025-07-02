@extends('layouts.app')

@section('content')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@400;600&display=swap');

body {
    background: linear-gradient(135deg, #f6f7fb 0%, #e9ecef 100%);
    min-height: 100vh;
    font-family: 'Poppins', 'Inter', sans-serif;
    color: #23272f;
}

    /* === GLOBAL STYLES === */
    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        box-sizing: border-box;
    }

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

        --primary: #5a67d8;
        --primary-hover: #4c51bf;
        --secondary: #718096;
        --secondary-hover: #4a5568;
        --danger: #e53e3e;
        --danger-hover: #c53030;
        --success: #48bb78;
        --error: #e53e3e;
        --border: #e2e8f0;
        --bg-light: #f8fafc;
    }

    /* === FORM CONTAINER === */
    .form-container {
        max-width: 900px;
        margin: 2.5rem auto;
        padding: 0 1rem;
    }

    .form-card {
        background: #fff;
        border-radius: 1.25rem;
        box-shadow: 0 6px 24px 0 rgba(44, 62, 80, 0.08);
        border: 1px solid #e3e6ed;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }

    .form-card:hover {
        box-shadow: 0 12px 32px 0 rgba(44, 62, 80, 0.13);
    }

    .form-inner {
        padding: 2.2rem 2rem;
    }

    /* === FORM ELEMENTS === */
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.18rem;
        font-weight: 700;
        color: #2d3a4a;
        margin-bottom: 1.3rem;
        padding-bottom: 0.6rem;
        border-bottom: 2px solid #e3e6ed;
        letter-spacing: 0.01em;
    }

    .section-title i {
        font-size: 1.2em;
        color: #6c63ff;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.2rem;
        margin-bottom: 2rem;
    }

    .input-group {
        position: relative;
        margin-bottom: 1.1rem;
    }

    .input-group label {
        font-size: 0.98rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.45rem;
        display: block;
    }

    .input-group input,
    .input-group select {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1.5px solid #dbe2ef;
        border-radius: 0.9rem;
        font-size: 1.03rem;
        background: #f8fafc;
        color: #23272f;
        transition: all 0.2s;
        margin-top: 0.18rem;
    }

    .input-group input:focus,
    .input-group select:focus {
        outline: none;
        border-color: #6c63ff;
        background: #fff;
        box-shadow: 0 0 0 2px #6c63ff22;
    }

    .input-group input::placeholder {
        color: #b0b3c6;
        opacity: 1;
    }

    /* === BUTTONS === */
    .button-group {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.9rem;
        font-weight: 600;
        font-size: 1.01rem;
        border: none;
        cursor: pointer;
        transition: all 0.18s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 8px 0 rgba(44,62,80,0.06);
    }

    .btn-primary {
        background: linear-gradient(90deg, #495af0 60%, #6c63ff 100%);
        color: #fff;
        border: none;
    }

    .btn-primary:hover, .btn-primary:focus {
        background: linear-gradient(90deg, #2d3a4a 60%, #495af0 100%);
        color: #fff;
    }

    .btn-secondary {
        background: #f8fafc;
        color: #495af0;
        border: 1.5px solid #e3e6ed;
    }

    .btn-secondary:hover, .btn-secondary:focus {
        background: #e3e6ed;
        color: #2d3a4a;
    }

    .btn-danger {
        background: #f8d7da;
        color: #b02a37;
        border: 1.5px solid #f5c2c7;
    }

    .btn-danger:hover, .btn-danger:focus {
        background: #f5c2c7;
        color: #842029;
    }

    /* === ALERTS === */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid;
        font-size: 1rem;
        background: #f8fafc;
        color: #23272f;
    }

    .alert-success {
        background: #e6f4ea;
        color: #2e7d32;
        border-left-color: #43aa8b;
    }

    .alert-error {
        background: #f8d7da;
        color: #b02a37;
        border-left-color: #f44336;
    }

    .alert ul {
        margin-top: 0.5rem;
        padding-left: 1.5rem;
    }

    /* === TAB STYLES === */
    .tab-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .tab-header {
        display: flex;
        border-bottom: 1.5px solid #e3e6ed;
        margin-bottom: 1.3rem;
        background: #f8fafc;
        border-radius: 1rem 1rem 0 0;
        overflow: hidden;
    }

    .tab-button {
        padding: 1rem 2rem;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        color: #495af0;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        font-size: 1.04rem;
        border-radius: 1rem 1rem 0 0;
        background: #f8fafc;
    }

    .tab-button.active, .tab-button:focus {
        color: #23272f;
        background: #fff;
        border-bottom: 3px solid #6c63ff;
        box-shadow: 0 2px 8px 0 #6c63ff22;
    }

    .tab-button:hover:not(.active) {
        background: #e3e6ed;
        color: #2d3a4a;
    }

    .tab-content {
        display: none;
        flex: 1;
        padding: 1.3rem 0 0 0;
        background: transparent;
    }

    .tab-content.active {
        display: block;
    }

    /* === EXPENSE TYPE SELECTOR === */
    .expense-type-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.3rem;
    }

    .expense-type-btn {
        padding: 0.7rem 1.3rem;
        border-radius: 0.7rem;
        background: #f8fafc;
        border: 1.5px solid #e3e6ed;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #495af0;
        font-weight: 600;
    }

    .expense-type-btn.active, .expense-type-btn:focus {
        background: #495af0;
        color: #fff;
        border-color: #495af0;
    }

    .expense-type-btn:hover:not(.active) {
        background: #e3e6ed;
        color: #2d3a4a;
    }

    /* === ITEM TABLE STYLES === */
    .item-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 1.3rem;
        background: #f8fafc;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px 0 rgba(44,62,80,0.04);
    }

    .item-table th,
    .item-table td {
        padding: 0.8rem 1rem;
    }

    .item-table th {
        background: #e3e6ed;
        color: #2d3a4a;
        font-weight: 700;
        border-bottom: 2px solid #dbe2ef;
        font-size: 0.99rem;
    }

    .item-table td {
        border-bottom: 1px solid #e3e6ed;
        color: #23272f;
        font-size: 0.99rem;
    }

    .item-table tr:last-child td {
        border-bottom: none;
    }

    .item-table tr:hover td {
        background: #f1f3f7;
    }

    /* === FILE UPLOAD STYLES === */
    .file-upload-container {
        border: 2px dashed #e3e6ed;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        background: #f8fafc;
        transition: border-color 0.2s, background 0.2s;
        margin-bottom: 1rem;
    }

    .file-upload-container:hover {
        border-color: #495af0;
        background: #e3e6ed;
    }

    .file-upload-container i {
        color: #495af0;
        margin-bottom: 0.5rem;
    }

    .file-preview img {
        max-width: 120px;
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px 0 #6c63ff22;
    }

    .file-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.97rem;
    }

    .remove-file-btn {
        color: #f44336;
        cursor: pointer;
        background: none;
        border: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .remove-file-btn:hover {
        color: #b02a37;
    }

    .total-display {
        font-size: 1.12rem;
        color: #2d3a4a;
        margin-bottom: 1.3rem;
        margin-top: 1rem;
        text-align: right;
        font-weight: 700;
    }

    /* === SCROLLBAR STYLES === */
    ::-webkit-scrollbar {
        width: 8px;
        background: #e3e6ed;
        border-radius: 8px;
    }
    ::-webkit-scrollbar-thumb {
        background: #6c63ff;
        border-radius: 8px;
    }

    /* === SELECT2 DROPDOWN MODERN === */
    .select2-container--default .select2-selection--single {
        background: #f8fafc;
        border: 1.5px solid #e3e6ed;
        border-radius: 0.9rem;
        height: 46px;
        padding: 0.5rem 1rem;
        font-size: 1.03rem;
        color: #23272f;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: none;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single.select2-selection--focus {
        border-color: #495af0;
        box-shadow: 0 0 0 2px #495af022;
        background: #fff;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #23272f;
        line-height: 2.1rem;
        padding-left: 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 1rem;
    }

    .select2-dropdown {
        border-radius: 0.9rem;
        border: 1.5px solid #e3e6ed;
        box-shadow: 0 8px 32px 0 rgba(44, 62, 80, 0.10);
        background: #fff;
        font-size: 1.03rem;
        color: #23272f;
        z-index: 9999;
    }

    .select2-results__option {
        padding: 0.7rem 1.2rem;
        border-radius: 0.7rem;
        transition: background 0.15s, color 0.15s;
    }

    .select2-results__option--highlighted {
        background: #e3e6ed !important;
        color: #495af0 !important;
    }

    .select2-results__option--selected {
        background: #495af0 !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #b0b3c6;
        opacity: 1;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: #f44336;
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }

    .select2-container {
        width: 100% !important;
    }

    /* Native select fallback */
    .input-group select:not(.select2-hidden-accessible) {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: #f8fafc url("data:image/svg+xml,%3Csvg width='16' height='16' fill='gray' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M4 6l4 4 4-4' stroke='%23495af0' stroke-width='2' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E") no-repeat right 1rem center/1.2em auto;
        padding-right: 2.5rem;
    }

    /* === RESPONSIVE ADJUSTMENTS === */
    @media (max-width: 768px) {
        .form-inner {
            padding: 1.1rem;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .tab-header {
            flex-direction: column;
        }
        .tab-button {
            width: 100%;
            border-radius: 1rem 1rem 0 0;
        }
        .button-group {
            flex-direction: column-reverse;
            gap: 0.75rem;
        }
        .btn {
            width: 100%;
            justify-content: center;
        }
        .expense-type-selector {
            flex-direction: column;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-inner">
            {{-- Notifications --}}
            @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Tab Container --}}
            <div class="tab-container">
                <div class="tab-header">
                    <button class="tab-button active" data-tab="income-tab">
                        <i class="fas fa-sign-in-alt"></i> Pemasukan
                    </button>
                    <button class="tab-button" data-tab="expense-tab">
                        <i class="fas fa-sign-out-alt"></i> Pengeluaran
                    </button>
                </div>

                {{-- Income Tab Content --}}
                <div id="income-tab" class="tab-content active">
                    <form action="{{ route('struks.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="section-title">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Buat Pemasukan Baru</span>
                        </div>

                        <div class="form-grid">
                            <div class="input-group">
                                <label for="nama_toko">
                                    <i class="fas fa-store mr-1"></i>
                                    Nama Toko
                                </label>
                                <input type="text" name="nama_toko" id="nama_toko" required>
                            </div>

                            <div class="input-group">
                                <label for="nomor_struk">
                                    <i class="fas fa-receipt mr-1"></i>
                                    Nomor Struk
                                </label>
                                <input type="text" name="nomor_struk" id="nomor_struk" required>
                            </div>

                            <div class="input-group">
                                <label for="tanggal_struk">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Tanggal Struk
                                </label>
                                <input type="date" name="tanggal_struk" id="tanggal_struk" required
                                    value="{{ old('tanggal_struk', date('Y-m-d')) }}">
                            </div>
                            <div class="input-group">
                                <label for="tanggal_keluar">
                                    <i class="fas fa-calendar-day mr-1"></i>
                                    Tanggal Keluar
                                </label>
                                <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                                    value="{{ old('tanggal_keluar', date('Y-m-d')) }}">

                            </div>
                        </div>

                        {{-- Items --}}
                        <div class="section-title">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Daftar Barang</span>
                        </div>

                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th>Gambar</th> <!-- Kolom baru -->
                                </tr>
                            </thead>
                            <tbody id="income-items-container">
                                <tr class="item-row" data-item="0">
                                    <td>
                                        <div class="input-group">
                                            <select name="items[0][nama]" class="select-barang" required>
                                                <option value="">Pilih Barang</option>
                                                @foreach($barangList as $barang)
                                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" name="items[0][jumlah]" class="jumlah" min="1"
                                                value="1" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" name="items[0][harga]" class="harga" min="0" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="subtotal-display" id="subtotal-0">Rp 0</div>
                                        <input type="hidden" name="items[0][subtotal]" class="subtotal" value="0">
                                    </td>
                                    <td>
                                        <button type="button" onclick="removeIncomeItem(this)" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="text-center mb-6">
                            <button type="button" onclick="addIncomeItem()" class="btn btn-secondary">
                                <i class="fas fa-plus mr-2"></i>Tambah Barang
                            </button>
                        </div>

                        <div class="total-display">
                            <strong>Total Pemasukan:</strong> <span id="income-total">Rp 0</span>
                            <input type="hidden" name="total_harga" id="total_harga" value="0">
                        </div>

                        {{-- Receipt Photo --}}
                        <div class="section-title">
                            <i class="fas fa-camera"></i>
                            <span>Foto Struk</span>
                        </div>

                        <div class="file-upload-container" onclick="document.getElementById('foto_struk').click()">
                            <input type="file" name="foto_struk" id="foto_struk" accept="image/*"
                                style="display: none;">
                            <div>
                                <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                <p>Klik untuk upload foto struk</p>
                                <p>Format: JPG, PNG (Maks. 2MB)</p>
                            </div>
                        </div>

                        <div class="file-preview" id="file-preview">
                            <img id="preview-image" src="#" alt="Preview Foto Struk" class="hidden">
                            <div class="file-info">
                                <span id="file-name"></span>
                                <button type="button" onclick="removePhoto()" class="remove-file-btn">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </div>
                        </div>

                        <div class="button-group">
                            <a href="{{ route('struks.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Pemasukan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Expense Tab Content --}}
                <div id="expense-tab" class="tab-content">
                    <div class="expense-type-selector">
                        <button type="button" class="expense-type-btn active" data-expense-type="manual">
                            <i class="fas fa-edit mr-2"></i>Input Manual
                        </button>
                        <button type="button" class="expense-type-btn" data-expense-type="from-income">
                            <i class="fas fa-exchange-alt mr-2"></i>Dari Pemasukan
                        </button>
                    </div>

                    {{-- Manual Expense Form --}}
                    <div id="manual-expense" class="expense-form">
                        <form action="{{ route('pengeluarans.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="section-title">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Buat Pengeluaran Manual</span>
                            </div>

                            <div class="form-grid">
                                <div class="input-group">
                                    <label for="expense_nama_toko">
                                        <i class="fas fa-store mr-1"></i>
                                        Nama Toko
                                    </label>
                                    <input type="text" name="nama_toko" id="expense_nama_toko" required>
                                </div>

                                <div class="input-group">
                                    <label for="expense_nomor_struk">
                                        <i class="fas fa-receipt mr-1"></i>
                                        Nomor Struk
                                    </label>
                                    <input type="text" name="nomor_struk" id="expense_nomor_struk" required>
                                </div>

                                <!-- inputan tanggal di pemasukan -->
                                <div class="input-group">
                                    <label for="expense_tanggal">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Tanggal Pengeluaran
                                    </label>
                                    <input type="date" name="tanggal" id="expense_tanggal" required
                                        value="{{ old('tanggal', $income->tanggal_keluar ?? date('Y-m-d')) }}">

                                </div>

                                <div class="input-group">
                                    <label for="pegawai_id">
                                        <i class="fas fa-user-tie mr-1"></i>
                                        Pegawai
                                    </label>
                                    <select name="pegawai_id" id="pegawai_id" required>
                                        <option value="">Pilih Pegawai</option>
                                        @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Items --}}
                            <div class="section-title">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Daftar Barang</span>
                            </div>

                            <table class="item-table">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="expense-items-container">
                                    <tr class="item-row" data-item="0">
                                        <td>
                                            <div class="input-group">
                                                <input type="text" name="items[0][nama]" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="items[0][jumlah]" class="expense-jumlah"
                                                    min="1" value="1" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="items[0][harga]" class="expense-harga"
                                                    min="0" required>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="subtotal-display" id="expense-subtotal-0">Rp 0</div>
                                            <input type="hidden" name="items[0][subtotal]" class="expense-subtotal"
                                                value="0">
                                        </td>
                                        <td>
                                            <button type="button" onclick="removeExpenseItem(this)"
                                                class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="text-center mb-6">
                                <button type="button" onclick="addExpenseItem()" class="btn btn-secondary">
                                    <i class="fas fa-plus mr-2"></i>Tambah Barang
                                </button>
                            </div>

                            <div class="total-display">
                                <strong>Total Pengeluaran:</strong> <span id="expense-total">Rp 0</span>
                                <input type="hidden" name="total" id="expense_total" value="0">
                            </div>

                            {{-- Payment Proof --}}
                            <div class="section-title">
                                <i class="fas fa-camera"></i>
                                <span>Bukti Pembayaran</span>
                            </div>

                            <div class="file-upload-container"
                                onclick="document.getElementById('bukti_pembayaran').click()">
                                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*"
                                    style="display: none;">
                                <div>
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                    <p>Klik untuk upload bukti pembayaran</p>
                                    <p>Format: JPG, PNG (Maks. 2MB)</p>
                                </div>
                            </div>

                            <div class="file-preview" id="expense-file-preview">
                                <img id="expense-preview-image" src="#" alt="Preview Bukti Pembayaran" class="hidden">
                                <div class="file-info">
                                    <span id="expense-file-name"></span>
                                    <button type="button" onclick="removeExpensePhoto()" class="remove-file-btn">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>

                            <div class="button-group">
                                <a href="{{ route('pengeluarans.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Simpan Pengeluaran
                                </button>
                            </div>
                        </form>
                    </div>


                    {{-- Expense From Income Form --}}
                    <div id="from-income-expense" class="expense-form hidden">
                        <form action="{{ route('pengeluarans.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="from_income" value="1">

                            <div class="section-title">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Buat Pengeluaran dari Pemasukan</span>
                            </div>

                            <div class="form-grid">
                                <div class="input-group">
                                    <label for="struk_id">
                                        <i class="fas fa-receipt mr-1"></i>
                                        Pilih Struk Pemasukan
                                    </label>
                                    <select name="struk_id" id="struk_id" required>
                                        <option value="">Pilih Struk</option>
                                        @foreach($struks as $struk)
                                        <option value="{{ $struk->id }}"
                                            data-total="{{ collect(json_decode($struk->items, true))->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0)) }}"
                                            data-toko="{{ $struk->nama_toko }}"
                                            data-nomor="{{ $struk->nomor_struk }}"
                                            data-tanggal="{{ $struk->tanggal_struk }}"
                                            data-keluar="{{ $struk->tanggal_keluar }}"> {{-- ✅ Tambahkan ini --}}
                                            {{ $struk->nama_toko }} - {{ $struk->nomor_struk }}
                                            (Rp{{ number_format(collect(json_decode($struk->items, true))->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0)), 0, ',', '.') }})
                                        </option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label for="from_income_pegawai_id">
                                        <i class="fas fa-user-tie mr-1"></i>
                                        Pegawai
                                    </label>
                                    <select name="pegawai_id" id="from_income_pegawai_id" required>
                                        <option value="">Pilih Pegawai</option>
                                        @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group">
                                    <label for="from_income_tanggal">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Tanggal Pengeluaran
                                    </label>
                                    <input type="date" name="tanggal" id="from_income_tanggal" required
                                        value="{{ old('tanggal', $income->tanggal_keluar ?? date('Y-m-d')) }}">

                                </div>


                                <div class="input-group">
                                    <label for="from_income_total">
                                        <i class="fas fa-coins mr-1"></i>
                                        Total Pemasukan
                                    </label>
                                    <input type="text" id="from_income_total" readonly>
                                </div>
                            </div>

                            {{-- Items Preview --}}
                            <div class="section-title">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Daftar Barang</span>
                            </div>

                            <div id="income-items-preview" class="mb-6">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-b border-gray-200">
                                            <tr>
                                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                                <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                                <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-list">
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Pilih struk untuk melihat daftar barang</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="file-preview" id="from-income-file-preview">
                                <img id="from-income-preview-image" src="#" alt="Preview Bukti Pembayaran" class="hidden">
                                <div class="file-info">
                                    <span id="from-income-file-name"></span>
                                    <button type="button" onclick="removeFromIncomePhoto()" class="remove-file-btn">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </div>

                            <div class="button-group">
                                <a href="{{ route('pengeluarans.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Simpan Pengeluaran
                                </button>
                            </div>
                        </form>

                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const strukSelect = document.getElementById("struk_id");
                                const tanggalField = document.getElementById("from_income_tanggal");

                                strukSelect.addEventListener("change", function() {
                                    const selectedOption = this.options[this.selectedIndex];
                                    const tanggalKeluar = selectedOption.getAttribute("data-keluar");

                                    if (tanggalKeluar) {
                                        tanggalField.value = tanggalKeluar;
                                    } else {
                                        tanggalField.value = ""; // Kosongkan jika tidak ada
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeImageModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalImageTitle"></h3>
                        <div class="mt-2">
                            <img id="modalImageContent" src="" alt="" class="w-full h-auto max-h-screen-70 object-contain">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeImageModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const strukSelect = document.getElementById('struk_id');
        const tanggalInput = document.getElementById('from_income_tanggal');

        strukSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const tanggalKeluar = selectedOption.getAttribute('data-keluar');
            if (tanggalKeluar) {
                tanggalInput.value = tanggalKeluar;
            } else {
                tanggalInput.value = '';
            }
        });
    });


    // Image modal functions
    function openImageModal(imageUrl, title) {
        if (!imageUrl) return;

        $('#modalImageContent').attr('src', imageUrl);
        $('#modalImageTitle').text(title || 'Gambar Barang');
        $('#imageModal').removeClass('hidden');
    }

    function closeImageModal() {
        $('#imageModal').addClass('hidden');
        $('#modalImageContent').attr('src', '');
        $('#modalImageTitle').text('');
    }
    $(document).ready(function() {
        // Initialize Select2
        function initSelect2() {
            $('.select-barang').select2({
                placeholder: "Pilih barang...",
                width: '100%'
            });

            $('#struk_id').select2({
                placeholder: "Pilih struk...",
                width: '100%'
            });

            $('#pegawai_id, #from_income_pegawai_id').select2({
                placeholder: "Pilih pegawai...",
                width: '100%'
            });
        }
        initSelect2();

        // Tab switching
        $('.tab-button').click(function() {
            const tabId = $(this).data('tab');

            // Update active tab button
            $('.tab-button').removeClass('active');
            $(this).addClass('active');

            // Update active tab content
            $('.tab-content').removeClass('active');
            $('#' + tabId).addClass('active');
        });

        // Expense type switching
        $('.expense-type-btn').click(function() {
            const expenseType = $(this).data('expense-type');

            // Update active button
            $('.expense-type-btn').removeClass('active');
            $(this).addClass('active');

            // Show corresponding form
            $('.expense-form').addClass('hidden');
            $(`#${expenseType}-expense`).removeClass('hidden');
        });

        // Event listener setelah Select2 diinisialisasi
        $('#struk_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const tanggalKeluar = selectedOption.data('keluar');
            console.log('Tanggal keluar:', tanggalKeluar); // Tambahkan ini untuk debug
            if (tanggalKeluar) {
                $('#from_income_tanggal').val(tanggalKeluar);
            } else {
                $('#from_income_tanggal').val('');
            }
        });

        // Update total when struk is selected
        $('#struk_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const total = selectedOption.data('total');
            const strukId = selectedOption.val();

            if (total) {
                $('#from_income_total').val(formatRupiah(total));

                // Fetch and display items
                if (strukId) {
                    fetchStrukItems(strukId);
                }
            } else {
                $('#from_income_total').val('');
                $('#items-list').html('<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Pilih struk untuk melihat daftar barang</td></tr>');
            }
        });


        // File upload preview for income
        $('#foto_struk').change(function(e) {
            previewImage(e, '#preview-image', '#file-name', '#file-preview');
        });

        // File upload preview for expense
        $('#bukti_pembayaran').change(function(e) {
            previewImage(e, '#expense-preview-image', '#expense-file-name', '#expense-file-preview');
        });

        // Initialize event listeners for existing income rows
        $('#income-items-container .item-row').each(function() {
            const row = $(this);
            row.find('.jumlah, .harga').on('input', function() {
                updateIncomeSubtotal(row);
            });
        });

        // Initialize event listeners for existing expense rows
        $('#expense-items-container .item-row').each(function() {
            const row = $(this);
            row.find('.expense-jumlah, .expense-harga').on('input', function() {
                updateExpenseSubtotal(row);
            });
        });
    });

    // Format currency
    function formatRupiah(angka) {
        if (!angka) return 'Rp 0';
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Image preview
    function previewImage(event, previewId, fileNameId, previewContainerId) {
        const input = event.target;
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result).removeClass('hidden');
                $(fileNameId).text(file.name);
                $(previewContainerId).removeClass('hidden');
            }

            reader.readAsDataURL(file);
        }
    }

    // Remove photo
    function removePhoto() {
        $('#foto_struk').val('');
        $('#preview-image').attr('src', '#').addClass('hidden');
        $('#file-name').text('');
        $('#file-preview').addClass('hidden');
    }

    function removeExpensePhoto() {
        $('#bukti_pembayaran').val('');
        $('#expense-preview-image').attr('src', '#').addClass('hidden');
        $('#expense-file-name').text('');
        $('#expense-file-preview').addClass('hidden');
    }


    // Fetch struk items for preview
    function fetchStrukItems(strukId) {
        $.ajax({
            url: `/struks/${strukId}/items`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const items = data.items;
                const fotoStruk = data.foto_struk;

                if (items.length > 0) {
                    let html = '';
                    items.forEach(item => {
                        const subtotal = (item.jumlah || 0) * (item.harga || 0);
                        const imageUrl = item.gambar ?
                            `/storage/${item.gambar}` :
                            (fotoStruk ? `/storage/struk_foto/${fotoStruk}` : '');

                        const gambar = imageUrl ?
                            `<img src="${imageUrl}" alt="${item.nama}" class="w-10 h-10 object-cover rounded mx-auto cursor-pointer hover:opacity-75">` :
                            '<span class="text-gray-400 italic text-xs">Tidak ada gambar</span>';

                        html += `
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">${item.nama || '-'}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            ${item.jumlah || '-'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            ${formatRupiah(item.harga || 0)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-indigo-600">
                            ${formatRupiah(subtotal)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            ${imageUrl ? 
                                `<a href="#" onclick="openImageModal('${imageUrl}', '${item.nama || 'Struk'}')" class="inline-block">
                                    ${gambar}
                                </a>` : 
                                gambar}
                        </td>
                    </tr>
                    `;
                    });
                    $('#items-list').html(html);
                } else {
                    $('#items-list').html('<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada barang dalam struk ini</td></tr>');
                }
            },
            error: function() {
                $('#items-list').html('<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">Gagal memuat daftar barang</td></tr>');
            }
        });
    }

    // File upload preview for from-income form
    $('#from_income_bukti_pembayaran').change(function(e) {
        previewImage(e, '#from-income-preview-image', '#from-income-file-name', '#from-income-file-preview');
    });

    function removeFromIncomePhoto() {
        $('#from_income_bukti_pembayaran').val('');
        $('#from-income-preview-image').attr('src', '#').addClass('hidden');
        $('#from-income-file-name').text('');
        $('#from-income-file-preview').addClass('hidden');
    }

    // Income items management
    let incomeItemIndex = 1;

    function addIncomeItem() {
        const container = $('#income-items-container');

        const newRow = $(`
                <tr class="item-row" data-item="${incomeItemIndex}">
                    <td>
                        <div class="input-group">
                            <select name="items[${incomeItemIndex}][nama]" class="select-barang" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barangList as $barang)
                                    <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" name="items[${incomeItemIndex}][jumlah]" class="jumlah" min="1" value="1" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" name="items[${incomeItemIndex}][harga]" class="harga" min="0" required>
                        </div>
                    </td>
                    <td>
                        <div class="subtotal-display" id="subtotal-${incomeItemIndex}">Rp 0</div>
                        <input type="hidden" name="items[${incomeItemIndex}][subtotal]" class="subtotal" value="0">
                    </td>
                    <td>
                        <button type="button" onclick="removeIncomeItem(this)" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

        container.append(newRow);

        // Reinitialize Select2 for new elements
        $('.select-barang').select2({
            placeholder: "Pilih barang...",
            width: '100%'
        });

        // Initialize event listeners for the new row
        newRow.find('.jumlah, .harga').on('input', function() {
            updateIncomeSubtotal(newRow);
        });

        incomeItemIndex++;
    }

    function removeIncomeItem(button) {
        const row = $(button).closest('.item-row');
        row.fadeOut(300, function() {
            row.remove();
            updateIncomeTotal();
        });
    }

    function updateIncomeSubtotal(row) {
        const itemId = row.data('item');
        const quantity = parseFloat(row.find('.jumlah').val()) || 0;
        const price = parseFloat(row.find('.harga').val()) || 0;
        const subtotal = quantity * price;

        $(`#subtotal-${itemId}`).text(formatRupiah(subtotal));
        row.find('.subtotal').val(subtotal);

        updateIncomeTotal();
    }

    function updateIncomeTotal() {
        let total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });

        $('#total_harga').val(total);
        $('#income-total').text(formatRupiah(total));
    }

    // Expense items management
    let expenseItemIndex = 1;

    function addExpenseItem() {
        const container = $('#expense-items-container');

        const newRow = $(`
                <tr class="item-row" data-item="${expenseItemIndex}">
                    <td>
                        <div class="input-group">
                            <input type="text" name="items[${expenseItemIndex}][nama]" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" name="items[${expenseItemIndex}][jumlah]" class="expense-jumlah" min="1" value="1" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" name="items[${expenseItemIndex}][harga]" class="expense-harga" min="0" required>
                        </div>
                    </td>
                    <td>
                        <div class="subtotal-display" id="expense-subtotal-${expenseItemIndex}">Rp 0</div>
                        <input type="hidden" name="items[${expenseItemIndex}][subtotal]" class="expense-subtotal" value="0">
                    </td>
                    <td>
                        <button type="button" onclick="removeExpenseItem(this)" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

        container.append(newRow);

        // Initialize event listeners for the new row
        newRow.find('.expense-jumlah, .expense-harga').on('input', function() {
            updateExpenseSubtotal(newRow);
        });

        expenseItemIndex++;
    }

    function removeExpenseItem(button) {
        const row = $(button).closest('.item-row');
        row.fadeOut(300, function() {
            row.remove();
            updateExpenseTotal();
        });
    }

    function updateExpenseSubtotal(row) {
        const itemId = row.data('item');
        const quantity = parseFloat(row.find('.expense-jumlah').val()) || 0;
        const price = parseFloat(row.find('.expense-harga').val()) || 0;
        const subtotal = quantity * price;

        $(`#expense-subtotal-${itemId}`).text(formatRupiah(subtotal));
        row.find('.expense-subtotal').val(subtotal);

        updateExpenseTotal();
    }

    function updateExpenseTotal() {
        let total = 0;
        $('.expense-subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });

        $('#expense_total').val(total);
        $('#expense-total').text(formatRupiah(total));
    }
</script>
@endsection