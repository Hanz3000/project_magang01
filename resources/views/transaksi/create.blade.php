@extends('layouts.app')

@section('content')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
{{-- Google Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #6366f1;
        --primary-dark: #4338ca;
        --secondary: #64748b;
        --secondary-light: #94a3b8;
        --secondary-dark: #475569;
        --success: #10b981;
        --error: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --light: #f8fafc;
        --dark: #0f172a;
        --gray: #e2e8f0;
        --gray-dark: #cbd5e1;
        --border-radius: 0.75rem;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: #f1f5f9;
        color: var(--dark);
        line-height: 1.5;
    }

    /* === FORM CONTAINER === */
    .form-container {
        max-width: 960px;
        margin: 2rem auto;
        padding: 0 1.25rem;
    }

    .form-card {
        background: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray);
        overflow: hidden;
        transition: var(--transition);
    }

    .form-card:hover {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .form-inner {
        padding: 2.5rem;
    }

    /* === FORM ELEMENTS === */
    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--gray);
        letter-spacing: -0.01em;
    }

    .section-title i {
        font-size: 1.2em;
        color: var(--primary);
        background: rgba(79, 70, 229, 0.1);
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .input-group {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .input-group label {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--secondary-dark);
        margin-bottom: 0.5rem;
        display: block;
    }

    .input-group input,
    .input-group select {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 1px solid var(--gray);
        border-radius: var(--border-radius);
        font-size: 1rem;
        background: var(--light);
        color: var(--dark);
        transition: var(--transition);
        margin-top: 0.25rem;
    }

    .input-group input:focus,
    .input-group select:focus {
        outline: none;
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .input-group input::placeholder {
        color: var(--secondary-light);
        opacity: 1;
    }

    /* === BUTTONS === */
    .button-group {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2.5rem;
    }

    .btn {
        padding: 0.875rem 1.75rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        border: none;
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        box-shadow: var(--shadow);
    }

    .btn-secondary {
        background: #fff;
        color: var(--primary);
        border: 1px solid var(--gray);
    }

    .btn-secondary:hover,
    .btn-secondary:focus {
        background: var(--light);
        color: var(--primary-dark);
        border-color: var(--gray-dark);
    }

    .btn-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .btn-danger:hover,
    .btn-danger:focus {
        background: #fecaca;
        color: #991b1b;
    }

    /* === ALERTS === */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.75rem;
        font-size: 1rem;
        background: var(--light);
        color: var(--dark);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        border-left: 4px solid;
    }

    .alert i {
        font-size: 1.25rem;
        margin-top: 0.125rem;
    }

    .alert-content {
        flex: 1;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border-left-color: var(--success);
    }

    .alert-error {
        background: #fef2f2;
        color: #b91c1c;
        border-left-color: var(--error);
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
        border-bottom: 1px solid var(--gray);
        margin-bottom: 1.5rem;
        background: var(--light);
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        overflow: hidden;
    }

    .tab-button {
        padding: 1rem 2rem;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        color: var(--primary);
        border-bottom: 3px solid transparent;
        transition: var(--transition);
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab-button.active,
    .tab-button:focus {
        color: var(--dark);
        background: #fff;
        border-bottom: 3px solid var(--primary);
    }

    .tab-button:hover:not(.active) {
        background: rgba(79, 70, 229, 0.05);
        color: var(--primary-dark);
    }

    .tab-content {
        display: none;
        flex: 1;
        padding: 1.5rem 0 0 0;
        background: transparent;
    }

    .tab-content.active {
        display: block;
    }

    /* === EXPENSE TYPE SELECTOR === */
    .expense-type-selector {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .expense-type-btn {
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius);
        background: var(--light);
        border: 1px solid var(--gray);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary);
        font-weight: 600;
        flex: 1;
        justify-content: center;
    }

    .expense-type-btn.active,
    .expense-type-btn:focus {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .expense-type-btn:hover:not(.active) {
        background: var(--gray);
        color: var(--dark);
    }

    /* === ITEM TABLE STYLES === */
    .item-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 1.5rem;
        background: #fff;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray);
    }

    .item-table th,
    .item-table td {
        padding: 1rem 1.25rem;
        text-align: left;
    }

    .item-table th {
        background: var(--light);
        color: var(--secondary-dark);
        font-weight: 600;
        border-bottom: 1px solid var(--gray);
    }

    .item-table td {
        border-bottom: 1px solid var(--gray);
        color: var(--dark);
    }

    .item-table tr:last-child td {
        border-bottom: none;
    }

    .item-table tr:hover td {
        background: rgba(79, 70, 229, 0.03);
    }

    /* === FILE UPLOAD STYLES === */
    .file-upload-container {
        border: 2px dashed var(--gray);
        border-radius: var(--border-radius);
        padding: 2.5rem;
        text-align: center;
        cursor: pointer;
        background: var(--light);
        transition: var(--transition);
        margin-bottom: 1.5rem;
    }

    .file-upload-container:hover {
        border-color: var(--primary);
        background: rgba(79, 70, 229, 0.05);
    }

    .file-upload-container i {
        color: var(--primary);
        margin-bottom: 1rem;
        font-size: 2.5rem;
    }

    .file-upload-container p {
        margin: 0.5rem 0;
        color: var(--secondary);
    }

    .file-upload-container .file-info {
        margin-top: 1.5rem;
        display: none;
    }

    .file-preview {
        display: none;
        margin-bottom: 1rem;
    }

    .file-preview img {
        max-width: 150px;
        border-radius: calc(var(--border-radius) - 0.25rem);
        box-shadow: var(--shadow-sm);
    }

    .remove-file-btn {
        color: var(--error);
        cursor: pointer;
        background: none;
        border: none;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 1rem;
    }

    .remove-file-btn:hover {
        color: #991b1b;
    }

    .total-display {
        font-size: 1.125rem;
        color: var(--dark);
        margin-bottom: 1.5rem;
        margin-top: 1rem;
        text-align: right;
        font-weight: 700;
        padding: 1rem;
        background: var(--light);
        border-radius: var(--border-radius);
    }

    /* === SELECT2 DROPDOWN STYLES === */
    .select2-container--default .select2-selection--single {
        background: var(--light);
        border: 1px solid var(--gray);
        border-radius: var(--border-radius);
        height: 48px;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        color: var(--dark);
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single.select2-selection--focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        background: #fff;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--dark);
        line-height: 1.5;
        padding-left: 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 1rem;
    }

    .select2-dropdown {
        border-radius: var(--border-radius);
        border: 1px solid var(--gray);
        box-shadow: var(--shadow-md);
        background: #fff;
        font-size: 1rem;
        color: var(--dark);
        z-index: 9999;
    }

    .select2-results__option {
        padding: 0.75rem 1.25rem;
        transition: var(--transition);
    }

    .select2-results__option--highlighted {
        background: rgba(79, 70, 229, 0.1) !important;
        color: var(--primary-dark) !important;
    }

    .select2-results__option--selected {
        background: var(--primary) !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: var(--secondary-light);
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: var(--error);
        font-size: 1.25rem;
        margin-right: 0.5rem;
    }

    .select2-container {
        width: 100% !important;
    }

    /* === HIDDEN UTILITY === */
    .hidden {
        display: none !important;
    }

    /* === MODAL STYLES === */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.7);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: #fff;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(20px);
        transition: var(--transition);
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--gray);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--secondary);
        transition: var(--transition);
    }

    .modal-close:hover {
        color: var(--error);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-image {
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: contain;
        border-radius: calc(var(--border-radius) - 0.25rem);
    }

    /* === RESPONSIVE ADJUSTMENTS === */
    @media (max-width: 768px) {
        .form-inner {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .tab-header {
            flex-direction: row;
            overflow-x: auto;
            padding-bottom: 0;
        }

        .tab-button {
            white-space: nowrap;
        }

        .button-group {
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .expense-type-selector {
            flex-direction: column;
        }

        .modal-content {
            width: 95%;
        }
    }

    @media (max-width: 480px) {
        .form-inner {
            padding: 1rem;
        }

        .section-title {
            font-size: 1.125rem;
        }

        .item-table th,
        .item-table td {
            padding: 0.75rem;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-inner">
            {{-- Notifications --}}
            @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div class="alert-content">
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="alert-content">
                    <strong>Terjadi kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
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
                                <input type="text" name="nama_toko" id="nama_toko" placeholder="Masukkan nama toko" required>
                            </div>

                            <div class="input-group">
                                <label for="nomor_struk">
                                    <i class="fas fa-receipt mr-1"></i>
                                    Nomor Struk
                                </label>
                                <input type="text" name="nomor_struk" id="nomor_struk" placeholder="Masukkan nomor struk" required>
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="income-items-container">
                                <tr class="item-row" data-item="0">
                                    <td>
                                        <div class="input-group">
                                            <select name="items[0][nama]" class="select-barang" required>
                                                <option value="">Pilih Barang</option>
                                                @foreach ($barangList as $barang)
                                                <option value="{{ $barang->nama_barang }}">
                                                    {{ $barang->nama_barang }}
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
                                            <input type="number" name="items[0][harga]" class="harga" min="0"
                                                required placeholder="0">
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
                            <input type="file" name="foto_struk" id="foto_struk" accept="image/*" style="display: none;">
                            <div>
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Klik untuk upload foto struk</p>
                                <p>Format: JPG, PNG (Maks. 2MB)</p>
                            </div>
                        </div>

                        {{-- Preview muncul di bawahnya --}}
                        <div id="file-preview-wrapper" class="mt-4 text-center hidden">
                            <img id="preview-image" src="#" alt="Preview" class="mx-auto rounded-lg shadow cursor-pointer" style="max-width:180px;max-height:180px;" onclick="openImageModal(this.src, 'Foto Struk')">
                            <div class="mt-2">
                                <span id="file-name" class="text-sm text-gray-600"></span>
                                <button type="button" onclick="removePhoto()" class="remove-file-btn ml-2">
                                    <i class="fas fa-times"></i> Hapus
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
                        <form action="{{ route('pengeluarans.store') }}" method="POST"
                            enctype="multipart/form-data">
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
                                    <input type="text" name="nama_toko" id="expense_nama_toko" placeholder="Masukkan nama toko" required>
                                </div>

                                <div class="input-group">
                                    <label for="expense_nomor_struk">
                                        <i class="fas fa-receipt mr-1"></i>
                                        Nomor Struk
                                    </label>
                                    <input type="text" name="nomor_struk" id="expense_nomor_struk" placeholder="Masukkan nomor struk" required>
                                </div>

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
                                        @foreach ($pegawais as $pegawai)
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
                                                <input type="text" name="items[0][nama]" placeholder="Nama barang" required>
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
                                                    min="0" placeholder="0" required>
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
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Klik untuk upload bukti pembayaran</p>
                                    <p>Format: JPG, PNG (Maks. 2MB)</p>
                                </div>
                                <div class="file-preview" id="expense-file-preview">
                                    <img id="expense-preview-image" src="#" alt="Preview" class="hidden" style="max-width:150px; border-radius:12px; margin:0 auto;">
                                </div>
                                <div class="file-info hidden">
                                    <span id="expense-file-name"></span>
                                    <button type="button" onclick="removeExpensePhoto()" class="remove-file-btn">
                                        <i class="fas fa-times"></i> Hapus
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
                        <form action="{{ route('pengeluarans.store') }}" method="POST"
                            enctype="multipart/form-data">
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
                                        @foreach ($struks as $struk)
                                        <option value="{{ $struk->id }}"
                                            data-total="{{ collect(json_decode($struk->items, true))->sum(fn($item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0)) }}"
                                            data-toko="{{ $struk->nama_toko }}"
                                            data-nomor="{{ $struk->nomor_struk }}"
                                            data-tanggal="{{ $struk->tanggal_struk }}"
                                            data-keluar="{{ $struk->tanggal_keluar }}">
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
                                        @foreach ($pegawais as $pegawai)
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
                                    <label for="from_income_tanggal_struk">
                                        <i class="fas fa-calendar-day mr-1"></i>
                                        Tanggal Masuk (Pemasukan)
                                    </label>
                                    <input type="date" id="from_income_tanggal_struk" name="tanggal_struk" required>
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
                                    <table class="item-table">
                                        <thead>
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan</th>
                                                <th>Subtotal</th>
                                                <th>Gambar</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-list">
                                            <tr>
                                                <td colspan="5" class="text-center text-gray-500">Pilih
                                                    struk untuk melihat daftar barang</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Payment Proof --}}
                            <div class="section-title">
                                <i class="fas fa-camera"></i>
                                <span>Bukti Pembayaran</span>
                            </div>

                            <div class="file-upload-container"
                                onclick="document.getElementById('from_income_bukti_pembayaran').click()">
                                <input type="file" name="bukti_pembayaran" id="from_income_bukti_pembayaran" accept="image/*" style="display: none;">
                                <div>
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Klik untuk upload bukti pembayaran</p>
                                    <p>Format: JPG, PNG (Maks. 2MB)</p>
                                </div>
                                <div class="file-preview" id="from-income-file-preview">
                                    <img id="from-income-preview-image" src="#" alt="Preview" class="hidden" style="max-width:150px; border-radius:12px; margin:0 auto;">
                                </div>
                                <div class="file-info hidden">
                                    <span id="from-income-file-name"></span>
                                    <button type="button" onclick="removeFromIncomePhoto()" class="remove-file-btn">
                                        <i class="fas fa-times"></i> Hapus
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalImageTitle"></h3>
            <button class="modal-close" onclick="closeImageModal()">&times;</button>
        </div>
        <div class="modal-body">
            <img id="modalImageContent" src="" alt="" class="modal-image">
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Format currency
    function formatRupiah(angka) {
        if (!angka) return 'Rp 0';
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Image preview function
    function previewImage(event, previewId, fileNameId, previewContainerId) {
        const input = event.target;
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result).removeClass('hidden');
                $(fileNameId).text(file.name);
                $(previewContainerId).closest('.file-upload-container').find('.file-preview, .file-info').removeClass('hidden');
            }

            reader.readAsDataURL(file);
        }
    }

    // Remove photo functions
    function removePhoto() {
        $('#foto_struk').val('');
        $('#preview-image').attr('src', '#');
        $('#file-name').text('');
        $('#file-preview-wrapper').addClass('hidden');
    }

    function removeExpensePhoto() {
        $('#bukti_pembayaran').val('');
        $('#expense-preview-image').attr('src', '#').addClass('hidden');
        $('#expense-file-name').text('');
        $('.file-preview, .file-info').addClass('hidden');
    }

    function removeFromIncomePhoto() {
        $('#from_income_bukti_pembayaran').val('');
        $('#from-income-preview-image').attr('src', '#').addClass('hidden');
        $('#from-income-file-name').text('');
        $('.file-preview, .file-info').addClass('hidden');
    }

    // Image modal functions
    function openImageModal(imageUrl, title) {
        if (!imageUrl) return;

        $('#modalImageContent').attr('src', imageUrl);
        $('#modalImageTitle').text(title || 'Gambar Barang');
        $('#imageModal').addClass('active');
        $('body').css('overflow', 'hidden');
    }

    function closeImageModal() {
        $('#imageModal').removeClass('active');
        $('#modalImageContent').attr('src', '');
        $('#modalImageTitle').text('');
        $('body').css('overflow', 'auto');
    }

    // Close modal when clicking outside
    $('#imageModal').click(function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

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
                $('#items-list').html(
                    '<tr><td colspan="5" class="text-center text-gray-500">Pilih struk untuk melihat daftar barang</td></tr>'
                );
            }
        });

        // Preview setelah upload
        $('#foto_struk').change(function(e) {
            const input = e.target;
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    $('#preview-image').attr('src', ev.target.result);
                    $('#file-preview-wrapper').removeClass('hidden');
                }
                reader.readAsDataURL(file);
                $('#file-name').text(file.name);
            }
        });

        // Hapus preview
        function removePhoto() {
            $('#foto_struk').val('');
            $('#preview-image').attr('src', '#');
            $('#file-name').text('');
            $('#file-preview-wrapper').addClass('hidden');
        }

        // Modal detail gambar (sudah ada di kode kamu)
        function openImageModal(imageUrl, title) {
            if (!imageUrl) return;
            $('#modalImageContent').attr('src', imageUrl);
            $('#modalImageTitle').text(title || 'Gambar');
            $('#imageModal').addClass('active');
            $('body').css('overflow', 'hidden');
        }

        // File upload preview for expense
        $('#bukti_pembayaran').change(function(e) {
            const input = e.target;
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    $('#expense-preview-image').attr('src', ev.target.result).removeClass('hidden');
                    $('#expense-file-preview').show();
                }
                reader.readAsDataURL(file);
                $('#expense-file-name').text(file.name);
                $('.file-info').removeClass('hidden');
            }
        });

        // File upload preview for from-income form
        $('#from_income_bukti_pembayaran').change(function(e) {
            const input = e.target;
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    $('#from-income-preview-image').attr('src', ev.target.result).removeClass('hidden');
                    $('#from-income-file-preview').show();
                }
                reader.readAsDataURL(file);
                $('#from-income-file-name').text(file.name);
                $('.file-info').removeClass('hidden');
            }
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

        // Set tanggal keluar when selecting a struk
        $('#struk_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const tanggalKeluar = selectedOption.data('keluar');
            const tanggalStruk = selectedOption.data('tanggal');

            if (tanggalKeluar) {
                $('#from_income_tanggal').val(tanggalKeluar);
            } else {
                $('#from_income_tanggal').val('');
            }

            if (tanggalStruk) {
                $('#from_income_tanggal_struk').val(tanggalStruk);
            } else {
                $('#from_income_tanggal_struk').val('');
            }
        });
    });

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
                            `<a href="#" onclick="openImageModal('${imageUrl}', '${item.nama || 'Struk'}')" class="inline-block">
                                <img src="${imageUrl}" alt="${item.nama}" class="w-10 h-10 object-cover rounded mx-auto cursor-pointer hover:opacity-75">
                            </a>` :
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
                                    ${gambar}
                                </td>
                            </tr>
                        `;
                    });
                    $('#items-list').html(html);
                } else {
                    $('#items-list').html(
                        '<tr><td colspan="5" class="text-center text-gray-500">Tidak ada barang dalam struk ini</td></tr>'
                    );
                }
            },
            error: function() {
                $('#items-list').html(
                    '<tr><td colspan="5" class="text-center text-red-500">Gagal memuat daftar barang</td></tr>'
                );
            }
        });
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
                            @foreach ($barangList as $barang)
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
                        <input type="number" name="items[${incomeItemIndex}][harga]" class="harga" min="0" required placeholder="0">
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
                        <input type="text" name="items[${expenseItemIndex}][nama]" placeholder="Nama barang" required>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" name="items[${expenseItemIndex}][jumlah]" class="expense-jumlah" min="1" value="1" required>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" name="items[${expenseItemIndex}][harga]" class="expense-harga" min="0" placeholder="0" required>
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