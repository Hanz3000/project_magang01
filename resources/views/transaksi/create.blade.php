@extends('layouts.app')

@section('content')
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
        margin-bottom: 1.5rem;
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

    /* === ENHANCED FILE UPLOAD STYLES === */
    .file-upload-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .file-upload-input {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-upload-label {
        display: block;
        border: 2px dashed var(--gray);
        border-radius: var(--border-radius);
        padding: 2.5rem;
        text-align: center;
        cursor: pointer;
        background: var(--light);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .file-upload-label:hover {
        border-color: var(--primary);
        background: rgba(79, 70, 229, 0.05);
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .file-upload-icon {
        font-size: 2.5rem;
        color: var(--primary);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .file-upload-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.5rem;
    }

    .file-upload-description {
        color: var(--secondary);
        margin-bottom: 0.25rem;
    }

    .file-upload-requirements {
        color: var(--secondary-light);
        font-size: 0.875rem;
    }

    .file-preview-container {
        margin-top: 1.5rem;
        animation: fadeIn 0.3s ease;
    }

    .file-preview-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: calc(var(--border-radius) - 0.25rem);
        box-shadow: var(--shadow-sm);
        margin: 0 auto;
        display: block;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid var(--gray);
    }

    .file-preview-image:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow);
    }

    .file-preview-info {
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .file-name {
        font-size: 0.875rem;
        color: var(--secondary-dark);
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius);
        background: rgba(239, 68, 68, 0.1);
    }

    .remove-file-btn:hover {
        color: #991b1b;
        background: rgba(239, 68, 68, 0.2);
    }

    .file-upload-label.has-file .file-upload-content {
        display: none;
    }

    .file-upload-label.has-file .file-preview-container {
        display: block;
    }

    .hidden {
        display: none;
    }

    /* === OPTIMIZED SELECT2 DROPDOWN STYLES === */
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
        line-height: 1.4;
        padding-left: 0;
        padding-right: 30px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Dropdown dengan lebar optimal */
    .select2-dropdown {
        border-radius: var(--border-radius);
        border: 1px solid var(--gray);
        box-shadow: var(--shadow-md);
        background: #fff;
        font-size: 0.95rem;
        color: var(--dark);
        z-index: 9999;
        /* Lebar akan di-set dinamis via JavaScript */
        width: auto !important;
        min-width: 280px;
        max-width: min(600px, 90vw);
    }

    .select2-results__options {
        max-height: 250px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--gray) transparent;
    }

    .select2-results__options::-webkit-scrollbar {
        width: 6px;
    }

    .select2-results__options::-webkit-scrollbar-track {
        background: transparent;
    }

    .select2-results__options::-webkit-scrollbar-thumb {
        background: var(--gray);
        border-radius: 3px;
    }

    .select2-results__option {
        padding: 0.75rem 1rem;
        transition: var(--transition);
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.4;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
    }

    .select2-results__option:last-child {
        border-bottom: none;
    }

    .select2-results__option--highlighted {
        background: rgba(79, 70, 229, 0.1) !important;
        color: var(--primary-dark) !important;
    }

    .select2-results__option--selected {
        background: var(--primary) !important;
        color: #fff !important;
        font-weight: 600;
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

    /* === ANIMATIONS === */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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

        .modal-content {
            width: 95%;
        }

        .select2-dropdown {
            max-width: 95vw;
            min-width: 250px;
        }
        
        .item-table td:nth-child(1) {
            min-width: 180px;
            width: 50%;
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

        .select2-dropdown {
            max-width: 90vw;
            min-width: 200px;
        }
    }

    /* === IMPROVED ITEM TABLE STYLES === */
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
        table-layout: fixed;
    }

    /* Perbaikan alignment untuk kolom Barang */
    .item-table td:nth-child(1) .input-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .item-table td:nth-child(1) .select2-container {
        margin-top: 0;
    }

    /* Penyesuaian lebar kolom untuk tabel pemasukan */
    .income-table th:nth-child(1),
    .income-table td:nth-child(1) {
        width: 45%;
        min-width: 280px;
    }

    .income-table th:nth-child(2),
    .income-table td:nth-child(2) {
        width: 15%;
    }

    .income-table th:nth-child(3),
    .income-table td:nth-child(3) {
        width: 20%;
    }

    .income-table th:nth-child(4),
    .income-table td:nth-child(4) {
        width: 20%;
    }

    .income-table th:nth-child(5),
    .income-table td:nth-child(5) {
        width: 10%;
    }

    /* Penyesuaian lebar kolom untuk tabel pengeluaran */
    .expense-table th:nth-child(1),
    .expense-table td:nth-child(1) {
        width: 70%;
        min-width: 280px;
    }

    .expense-table th:nth-child(2),
    .expense-table td:nth-child(2) {
        width: 20%;
    }

    .expense-table th:nth-child(3),
    .expense-table td:nth-child(3) {
        width: 10%;
    }

    .item-table th {
        background: var(--light);
        color: var(--secondary-dark);
        font-weight: 600;
        border-bottom: 1px solid var(--gray);
        padding: 0.75rem 1rem;
        text-align: center;
    }

    .item-table td {
        border-bottom: 1px solid var(--gray);
        color: var(--dark);
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }

    /* Perbaikan untuk input group dalam tabel */
    .item-table .input-group {
        margin-bottom: 0;
    }

    .item-table .input-group input {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    /* Perbaikan untuk select2 dalam tabel */
    .item-table .select2-container--default .select2-selection--single {
        height: 40px;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }

    /* Perbaikan untuk tombol hapus */
    .item-table .btn-danger {
        padding: 0.5rem;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .item-table .btn-danger i {
        margin: 0;
    }

    /* Perbaikan untuk stok info */
    .stok-info {
        display: block;
        font-size: 0.75rem;
        color: var(--secondary);
        margin-top: 0.25rem;
        text-align: left;
    }

    /* Perataan tengah untuk kolom jumlah dan aksi */
    .item-table td:nth-child(2),
    .item-table td:nth-child(3) {
        text-align: center;
    }

    .total-display {
        text-align: right;
        font-size: 1.125rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: var(--light);
        border-radius: var(--border-radius);
        border: 1px solid var(--gray);
    }
</style>

<!-- Income/Expense Form -->
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
                        <span>Buat Pemasukan</span>
                    </div>

                    <div class="form-grid">
                        <div class="input-group">
                            <label for="nama_toko">
                                <i class="fas fa-store mr-1"></i>
                                Nama Toko
                            </label>
                            <input type="text" name="nama_toko" id="nama_toko" placeholder="Masukkan nama toko"
                                required>
                        </div>

                        <div class="input-group">
                            <label for="nomor_struk">
                                <i class="fas fa-receipt mr-1"></i>
                                Nomor Struk
                            </label>
                            <input type="text" name="nomor_struk" id="nomor_struk"
                                placeholder="Masukkan nomor struk" required>
                        </div>

                        <div class="input-group">
                            <label for="tanggal_struk">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Tanggal Masuk
                            </label>
                            <input type="date" name="tanggal_struk" id="tanggal_struk" required
                                value="{{ old('tanggal_struk', date('Y-m-d')) }}">
                        </div>
                        <div class="input-group">
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="section-title">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Daftar Barang</span>
                    </div>

                    <table class="item-table income-table">
                        <thead>
                            <tr>
                                <th>Barang</th>
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
                                            <option value="{{ $barang->kode_barang }}">
                                                {{ $barang->nama_barang }} ({{ $barang->kode_barang }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" name="items[0][jumlah]" class="jumlah" min="1" value="1" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" name="items[0][harga]" class="harga" min="0" required placeholder="0">
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

                    <div class="file-upload-wrapper">
                        <input type="file" name="foto_struk" id="foto_struk" accept="image/*" class="file-upload-input" onchange="previewUploadedImage(this)">
                        <label for="foto_struk" class="file-upload-label" id="file-upload-label">
                            <div class="file-upload-content text-center">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h4 class="file-upload-title">Upload Foto Struk</h4>
                                <p class="file-upload-description">Seret & lepas file di sini atau klik untuk memilih</p>
                                <p class="file-upload-requirements">Format: JPG, PNG (Maks. 2MB)</p>
                            </div>
                            <div class="file-preview-container hidden" id="file-preview-container">
                                <img id="preview-image" src="#" alt="Preview" class="file-preview-image">
                                <div class="file-preview-info">
                                    <span id="file-name" class="file-name"></span>
                                    <button type="button" onclick="removePhoto()" class="remove-file-btn">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </label>
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

            <!-- PENGELUARANNNNN -->
            {{-- Expense Tab Content --}}
            <div id="expense-tab" class="tab-content">
                <form action="{{ route('pengeluarans.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Buat Pengeluaran</span>
                    </div>

                    <div class="form-grid">
                        <div class="input-group">
                            <label for="expense_nama_spk">
                                <i class="fas fa-file-signature mr-1"></i>
                                Nama SPK
                            </label>
                            <input 
                                type="text" 
                                name="nama_spk" 
                                id="expense_nama_spk"
                                class="form-input w-full bg-gray-100 text-gray-500 opacity-80 cursor-not-allowed"
                                placeholder="Terisi otomatis" 
                                readonly>
                            <small class="text-gray-400">Format: SPK-[DIVISI]-[NIP 2 digit]-[001]</small>
                        </div>

                        <div class="input-group">
                            <label for="expense_nomor_struk">
                                <i class="fas fa-receipt mr-1"></i>
                                Nomor Struk
                            </label>
                            <input type="text" name="nomor_struk" id="expense_nomor_struk"
                                   value="{{ $nextSpkNumber ?? '' }}"
                                   class="form-input w-full bg-gray-100 text-gray-500 placeholder-gray-400 opacity-80 cursor-not-allowed"
                                   placeholder="Terisi otomatis" readonly>
                            <small class="text-gray-400">Format: spk/DD/MM/YYXXXXX</small>
                        </div>
                        
                        <div class="input-group">
                            <label for="expense_tanggal">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Tanggal Pengeluaran
                            </label>
                            <input type="date" name="tanggal" id="expense_tanggal" required
                                value="{{ old('tanggal', date('Y-m-d')) }}">
                        </div>

                        <div class="input-group">
                            <label for="pegawai_id">
                                <i class="fas fa-user-tie mr-1"></i>
                                Pegawai
                            </label>
                            <select name="pegawai_id" id="pegawai_id" required>
                                <option value="">Pilih Pegawai</option>
                                @foreach ($pegawais as $pegawai)
                                <option value="{{ $pegawai->id }}" data-divisi="{{ $pegawai->divisi }}">
                                    {{ $pegawai->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="divisi_display">
                                <i class="fas fa-sitemap mr-1"></i>
                                Divisi
                            </label>
                            <input type="text" id="divisi_display"
                                class="form-input w-full bg-gray-100 text-gray-700 placeholder-gray-400"
                                placeholder="Terisi otomatis" readonly>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="section-title">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Daftar Barang</span>
                    </div>

                    <table class="item-table expense-table">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="expense-items-container">
                            <tr class="item-row" data-item="0">
                                <td>
                                    <div class="input-group">
                                        <select name="items[0][nama]" class="select-barang" required onchange="updateStokExpense(this)">
                                            <option value="">Pilih Barang</option>
                                            @foreach ($barangList as $barang)
                                            <option value="{{ $barang->kode_barang }}" data-stok="{{ $barang->jumlah }}">
                                                {{ $barang->nama_barang }} ({{ $barang->kode_barang }}) - Stok: {{ $barang->jumlah }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <span class="stok-info">Stok: -</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" name="items[0][jumlah]" class="jumlah" min="1"
                                            value="1" required>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" onclick="removeExpenseItem(this)" class="btn btn-danger">
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

<!-- Image Modal -->
<div id="imageModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalImageTitle"></h3>
            <button class="modal-close" onclick="closeImageModal()">×</button>
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
    // Global variables
    let incomeIndex = 1;
    let expenseItemIndex = 1;

    // Format currency
    function formatRupiah(angka) {
        if (!angka) return 'Rp 0';
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Calculate optimal dropdown width based on content
    function calculateOptimalDropdownWidth(selectElement) {
        const $select = $(selectElement);
        const options = $select.find('option');
        let maxLength = 0;
        let longestText = '';

        // Find the longest option text
        options.each(function() {
            const text = $(this).text().trim();
            if (text.length > maxLength && text !== '') {
                maxLength = text.length;
                longestText = text;
            }
        });

        // Create a temporary element to measure text width
        const $temp = $('<span>').css({
            'font-family': $('.select2-dropdown').css('font-family') || 'Plus Jakarta Sans, sans-serif',
            'font-size': $('.select2-dropdown').css('font-size') || '0.95rem',
            'font-weight': 'normal',
            'position': 'absolute',
            'visibility': 'hidden',
            'white-space': 'nowrap',
            'padding': '0.75rem 1rem'
        }).text(longestText).appendTo('body');

        const textWidth = $temp.outerWidth();
        $temp.remove();

        // Calculate optimal width with padding and arrow space
        const minWidth = 280;
        const maxWidth = Math.min(600, $(window).width() * 0.9);
        const optimalWidth = Math.max(minWidth, Math.min(textWidth + 60, maxWidth));

        return optimalWidth;
    }

    // Preview uploaded image - GLOBAL FUNCTION
    function previewUploadedImage(input, type = 'income') {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            let previewId, fileNameId, containerId, labelId;

            if (type === 'income') {
                previewId = '#preview-image';
                fileNameId = '#file-name';
                containerId = '#file-preview-container';
                labelId = '#file-upload-label';
            } else {
                previewId = '#expense-preview-image';
                fileNameId = '#expense-file-name';
                containerId = '#expense-file-preview-container';
                labelId = '#expense-file-upload-label';
            }

            $(previewId).attr('src', e.target.result);
            $(fileNameId).text(file.name);
            $(containerId).removeClass('hidden');
            $(labelId).addClass('has-file');
        };
        reader.readAsDataURL(file);
    }

    // Remove photo functions - GLOBAL FUNCTIONS
    function removePhoto() {
        $('#foto_struk').val('');
        $('#preview-image').attr('src', '#');
        $('#file-name').text('');
        $('#file-preview-container').addClass('hidden');
        $('#file-upload-label').removeClass('has-file');
    }

    function removeExpensePhoto() {
        $('#bukti_pembayaran').val('');
        $('#expense-preview-image').attr('src', '#');
        $('#expense-file-name').text('');
        $('#expense-file-preview-container').addClass('hidden');
        $('#expense-file-upload-label').removeClass('has-file');
    }

    // Image modal functions - GLOBAL FUNCTIONS
    function openImageModal(imageUrl, title) {
        if (!imageUrl) return;
        $('#modalImageContent').attr('src', imageUrl);
        $('#modalImageTitle').text(title || 'Gambar');
        $('#imageModal').addClass('active');
        $('body').css('overflow', 'hidden');
    }

    function closeImageModal() {
        $('#imageModal').removeClass('active');
        $('#modalImageContent').attr('src', '');
        $('#modalImageTitle').text('');
        $('body').css('overflow', 'auto');
    }

    // Update stok for expense - GLOBAL FUNCTION
    function updateStokExpense(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const stokAsli = parseInt(selectedOption.getAttribute('data-stok')) || 0;
        const row = selectElement.closest('tr');
        const jumlahInput = row.querySelector('.jumlah');
        const stokInfo = row.querySelector('.stok-info');
        const jumlah = parseInt(jumlahInput?.value || 0);
        const sisa = stokAsli - jumlah;

        stokInfo.textContent = `Stok: ${sisa >= 0 ? sisa : 0}`;

        // Remove existing event listener to prevent duplicates
        jumlahInput.removeEventListener('input', jumlahInput.stockUpdateHandler);
        
        // Create new event handler
        jumlahInput.stockUpdateHandler = function() {
            const inputJumlah = parseInt(jumlahInput.value) || 0;
            const sisaBaru = stokAsli - inputJumlah;

            if (inputJumlah > stokAsli) {
                alert("Jumlah melebihi stok tersedia!");
                jumlahInput.value = stokAsli;
                stokInfo.textContent = `Stok: 0`;
            } else {
                stokInfo.textContent = `Stok: ${sisaBaru >= 0 ? sisaBaru : 0}`;
            }
        };

        // Add new event listener
        jumlahInput.addEventListener('input', jumlahInput.stockUpdateHandler);
    }

    // Income items management - GLOBAL FUNCTIONS
    function addIncomeItem() {
        const container = document.getElementById('income-items-container');
        if (!container) return;

        const oldRow = container.querySelector('.item-row');
        const newRow = oldRow.cloneNode(true);
        
        // Update subtotal ID
        newRow.innerHTML = newRow.innerHTML.replace(/id="subtotal-0"/g, `id="subtotal-${incomeIndex}"`);

        // Replace all [0] with [incomeIndex]
        const regex = /\[0\]/g;
        newRow.innerHTML = newRow.innerHTML.replace(regex, `[${incomeIndex}]`);
        newRow.setAttribute('data-item', incomeIndex);

        // Reset inputs
        const inputs = newRow.querySelectorAll('input');
        inputs.forEach(input => {
            if (input.type === 'number') {
                input.value = input.classList.contains('jumlah') ? 1 : 0;
            }
            if (input.type === 'hidden') {
                input.value = 0;
            }
        });

        // Reset select dropdown
        const select = newRow.querySelector('.select-barang');
        select.selectedIndex = 0;
        
        // Remove existing Select2 container
        $(select).next('.select2-container').remove();

        // Initialize Select2 with optimal width
        initializeSelect2ForElement(select);

        // Add event listeners for quantity and price inputs
        const jumlahInput = newRow.querySelector('.jumlah');
        const hargaInput = newRow.querySelector('.harga');
        
        jumlahInput.addEventListener('input', function() {
            updateIncomeSubtotal($(newRow));
        });

        hargaInput.addEventListener('input', function() {
            updateIncomeSubtotal($(newRow));
        });

        container.appendChild(newRow);
        incomeIndex++;
    }

    function removeIncomeItem(button) {
        const row = $(button).closest('.item-row');
        const container = document.getElementById('income-items-container');
        if (container.querySelectorAll('.item-row').length > 1) {
            row.fadeOut(300, function() {
                row.remove();
                updateIncomeTotal();
            });
        }
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
        $('#income-items-container .subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });

        $('#total_harga').val(total);
        $('#income-total').text(formatRupiah(total));
    }

    // Expense items management - GLOBAL FUNCTIONS
    function addExpenseItem() {
        const container = $('#expense-items-container');
        if (!container.length) return;

        const oldRow = container.find('.item-row').first();
        const newRow = oldRow.clone();

        // Update index
        newRow.attr('data-item', expenseItemIndex);
        newRow.html(newRow.html().replace(/\[0\]/g, `[${expenseItemIndex}]`));

        // Reset inputs
        newRow.find('input').each(function() {
            if (this.type === 'number') {
                this.value = this.classList.contains('jumlah') ? 1 : 0;
            }
        });

        // Reset select
        const select = newRow.find('.select-barang');
        select.val('');
        select.next('.select2-container').remove();

        // Re-initialize Select2 with optimal width
        initializeSelect2ForElement(select[0]);

        // Reset stock info
        newRow.find('.stok-info').text('Stok: -');

        // Add event listener for select change
        select.on('change', function() {
            updateStokExpense(this);
        });

        container.append(newRow);
        expenseItemIndex++;
    }

    function removeExpenseItem(button) {
        const row = $(button).closest('.item-row');
        const container = document.getElementById('expense-items-container');
        if (container.querySelectorAll('.item-row').length > 1) {
            row.fadeOut(300, function() {
                row.remove();
            });
        }
    }

    // Initialize Select2 for a single element with optimal width
    function initializeSelect2ForElement(element) {
        const $element = $(element);
        const isBarangSelect = $element.hasClass('select-barang');
        
        if (isBarangSelect) {
            const optimalWidth = calculateOptimalDropdownWidth(element);
            
            $element.select2({
                placeholder: "Pilih barang...",
                width: '100%',
                dropdownAutoWidth: false,
                closeOnSelect: true,
                dropdownParent: $('.form-card'),
                dropdownCssClass: 'select2-dropdown-optimal'
            }).on('select2:open', function() {
                // Set optimal width when dropdown opens
                const dropdown = $element.data('select2').$dropdown;
                dropdown.css({
                    'width': optimalWidth + 'px',
                    'min-width': '280px',
                    'max-width': Math.min(600, $(window).width() * 0.9) + 'px'
                });
            });
        } else {
            // Standard initialization for other selects
            $element.select2({
                placeholder: "Pilih...",
                width: '100%',
                dropdownAutoWidth: true,
                closeOnSelect: true,
                dropdownParent: $('.form-card')
            });
        }
    }

    // Initialize Select2 - GLOBAL FUNCTION
    function initSelect2() {
        // Initialize barang selections with optimal width
        $('.select-barang').each(function() {
            initializeSelect2ForElement(this);
        });

        // Initialize pegawai selection (standard)
        if ($('#pegawai_id').length) {
            initializeSelect2ForElement($('#pegawai_id')[0]);
        }
    }

    // Document ready
    $(document).ready(function() {
        // Initialize Select2
        initSelect2();

        // Tab switching
        $('.tab-button').on('click', function(e) {
            e.preventDefault();
            const tabId = $(this).data('tab');
            $('.tab-button').removeClass('active');
            $(this).addClass('active');
            $('.tab-content').removeClass('active');
            $('#' + tabId).addClass('active');
        });

        // Initialize event listeners for income rows
        if ($('#income-items-container .item-row').length) {
            $('#income-items-container .item-row').each(function() {
                const row = $(this);
                row.find('.jumlah, .harga').on('input', function() {
                    updateIncomeSubtotal(row);
                });
            });
        }

        // Close modal when clicking outside
        $('#imageModal').click(function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Autofill divisi, generate Nama SPK & Nomor Struk saat pegawai dipilih
        if ($('#pegawai_id').length) {
            $('#pegawai_id').on('change', function() {
                const pegawaiId = $(this).val();
                $('#divisi_display').val('');
                $('#expense_nama_spk').val('');
                $('#expense_nomor_struk').val('');

                if (pegawaiId) {
                    // Generate nama SPK dan divisi
                    $.ajax({
                        url: '/generate-spk',
                        method: 'POST',
                        data: { 
                            pegawai_id: pegawaiId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#expense_nama_spk').val(response.nama_spk || 'Tidak ada nama SPK');
                            $('#divisi_display').val(response.divisi || 'Tidak diketahui');
                        },
                        error: function(xhr) {
                            console.error('Error generating SPK:', xhr.responseText);
                            $('#expense_nama_spk').val('Gagal generate Nama SPK');
                            $('#divisi_display').val('Gagal memuat divisi');
                        }
                    });

                    // Generate nomor struk otomatis
                    $.ajax({
                        url: '/pengeluarans/generate-nomor-struk',
                        method: 'GET',
                        data: { pegawai_id: pegawaiId },
                        success: function(response) {
                            $('#expense_nomor_struk').val(response.nomor_struk || 'Tidak ada nomor struk');
                        },
                        error: function(xhr) {
                            console.error('Error generating nomor struk:', xhr.responseText);
                            $('#expense_nomor_struk').val('Gagal generate nomor struk');
                        }
                    });
                }
            });
        }

        // Handle Select2 close event to ensure dropdown closes properly
        $(document).on('select2:close', '.select-barang, #pegawai_id', function() {
            $(this).blur();
        });

        // Ensure Select2 dropdown closes when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.select2-container, .select2-dropdown').length) {
                $('.select2-container--open').find('.select2-selection').trigger('blur');
            }
        });

        // Handle window resize to recalculate dropdown widths
        $(window).on('resize', function() {
            $('.select-barang').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    const optimalWidth = calculateOptimalDropdownWidth(this);
                    $(this).data('optimal-width', optimalWidth);
                }
            });
        });
    });
</script>

@endsection