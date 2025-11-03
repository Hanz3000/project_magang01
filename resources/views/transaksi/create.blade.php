@extends('layouts.app')

@section('content')
<style>
    /* PERBAIKAN: Semua variabel warna dipindahkan ke dark mode monokrom */
    :root {
        --primary: #9CA3AF; /* gray-400 */
        --primary-light: #F9FAFB; /* gray-100 */
        --primary-dark: #E5E7EB; /* gray-200 */
        --secondary: #6B7280; /* gray-500 */
        --secondary-light: #4B5563; /* gray-600 */
        --secondary-dark: #374151; /* gray-700 */
        --success: #10B981;
        --error: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
        --light: #1F2937; /* gray-800 */
        --dark: #F9FAFB; /* gray-100 */
        --gray: #374151; /* gray-700 */
        --gray-dark: #4B5563; /* gray-600 */
        --border-radius: 0.75rem;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    /* Body background diatur oleh layouts.app.blade.php */

    .form-container {
        max-width: 960px;
        margin: 0 auto; /* Dihapus margin-top agar menempel di layout */
        padding: 0; /* Dihapus padding agar pas di layout */
        width: 100%;
    }

    .form-card {
        background: #111827; /* bg-gray-900 */
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--gray);
        overflow: hidden;
        transition: var(--transition);
        margin-bottom: 1rem;
    }

    .form-card:hover {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .form-inner {
        padding: 1.5rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--gray);
        letter-spacing: -0.01em;
    }

    .section-title i {
        font-size: 1.1em;
        color: var(--primary-light); /* gray-100 */
        background: var(--secondary-dark); /* gray-700 */
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .input-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .input-group label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #D1D5DB; /* text-gray-300 */
        margin-bottom: 0.25rem;
        display: block;
    }

    .input-group input,
    .input-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray);
        border-radius: var(--border-radius);
        font-size: 0.9375rem;
        background: var(--light); /* gray-800 */
        color: var(--dark); /* gray-100 */
        transition: var(--transition);
        margin-top: 0.125rem;
    }

    .input-group input:focus,
    .input-group select:focus {
        outline: none;
        border-color: var(--primary); /* gray-400 */
        background: #374151; /* gray-700 */
        box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.2); /* ring-gray-400/20 */
    }

    .input-group input::placeholder {
        color: var(--secondary-light); /* gray-600 */
        opacity: 1;
    }

    .button-group {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn {
        padding: 0.75rem 1.25rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        font-size: 0.9375rem;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-sm);
        width: 100%;
        text-align: center;
    }

    /* PERBAIKAN: Tombol Primary diubah ke monokrom */
    .btn-primary {
        background: #E5E7EB; /* gray-200 */
        color: #111827; /* gray-900 */
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background: #F3F4F6; /* gray-100 */
        box-shadow: var(--shadow);
    }

    /* PERBAIKAN: Tombol Secondary diubah ke monokrom */
    .btn-secondary {
        background: #374151; /* gray-700 */
        color: #F9FAFB; /* gray-100 */
        border: 1px solid #4B5563; /* border-gray-600 */
    }

    .btn-secondary:hover,
    .btn-secondary:focus {
        background: #4B5563; /* gray-600 */
        color: #FFF;
        border-color: #6B7280; /* gray-500 */
    }

    .btn-danger {
        background: #312626; /* red-900/50 */
        color: #F87171; /* red-400 */
        border: 1px solid #7f1d1d; /* red-800 */
    }

    .btn-danger:hover,
    .btn-danger:focus {
        background: #450a0a; /* red-900 */
        color: #FCA5A5; /* red-300 */
    }

    /* PERBAIKAN: Alert diubah ke dark mode */
    .alert {
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1rem;
        font-size: 0.9375rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        border-left: 4px solid;
    }

    .alert i {
        font-size: 1.125rem;
        margin-top: 0.125rem;
    }

    .alert-content {
        flex: 1;
    }

    .alert-success {
        background: #064E3B; /* green-900 */
        color: #A7F3D0; /* green-200 */
        border-left-color: var(--success);
    }

    .alert-error {
        background: #450a0a; /* red-900 */
        color: #FCA5A5; /* red-300 */
        border-left-color: var(--error);
    }

    .alert ul {
        margin-top: 0.25rem;
        padding-left: 1rem;
    }

    .tab-container {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .tab-header {
        display: flex;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--gray);
        margin-bottom: 1rem;
        background: var(--light); /* gray-800 */
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .tab-button {
        padding: 0.75rem 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        color: var(--secondary); /* gray-500 */
        border-bottom: 3px solid transparent;
        transition: var(--transition);
        font-size: 0.9375rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        text-align: center;
        justify-content: center;
    }

    .tab-button.active,
    .tab-button:focus {
        color: var(--dark); /* gray-100 */
        background: #111827; /* gray-900 */
        border-bottom: 3px solid var(--primary-light); /* gray-100 */
    }

    .tab-button:hover:not(.active) {
        background: #374151; /* gray-700 */
        color: var(--primary-light); /* gray-100 */
    }

    .tab-content {
        display: none;
        flex: 1;
        padding: 1rem 0 0 0;
        background: transparent;
    }

    .tab-content.active {
        display: block;
    }

    /* PERBAIKAN: Upload diubah ke dark mode */
    .file-upload-wrapper {
        position: relative;
        margin-bottom: 1rem;
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
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        background: var(--light); /* gray-800 */
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .file-upload-label:hover {
        border-color: var(--primary); /* gray-400 */
        background: #374151; /* gray-700 */
        box-shadow: var(--shadow);
    }

    .file-upload-icon {
        font-size: 2rem;
        color: var(--primary-light); /* gray-100 */
        margin-bottom: 0.75rem;
    }

    .file-upload-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark); /* gray-100 */
        margin-bottom: 0.25rem;
    }

    .file-upload-description {
        color: var(--secondary); /* gray-500 */
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .file-upload-requirements {
        color: var(--secondary-light); /* gray-600 */
        font-size: 0.75rem;
    }

    .file-preview-container {
        margin-top: 1rem;
        animation: fadeIn 0.3s ease;
    }

    .file-preview-image {
        max-width: 100%;
        max-height: 150px;
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
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .file-name {
        font-size: 0.75rem;
        color: var(--secondary-dark); /* gray-300 */
        max-width: 150px;
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
        padding: 0.5rem 0.75rem;
        border-radius: var(--border-radius);
        background: rgba(239, 68, 68, 0.1);
    }

    .remove-file-btn:hover {
        color: #fca5a5; /* red-300 */
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

    /* PERBAIKAN: Select2 diubah ke dark mode */
    .select2-container--default .select2-selection--single {
        background: var(--light); /* gray-800 */
        border: 1px solid var(--gray);
        border-radius: var(--border-radius);
        height: 40px;
        padding: 0.5rem 1rem;
        font-size: 0.9375rem;
        color: var(--dark); /* gray-100 */
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default .select2-selection--single.select2-selection--focus {
        border-color: var(--primary); /* gray-400 */
        box-shadow: 0 0 0 3px rgba(156, 163, 175, 0.2);
        background: #374151; /* gray-700 */
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--dark); /* gray-100 */
        line-height: 1.4;
        padding-left: 0;
        padding-right: 30px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #9CA3AF transparent transparent transparent; /* border-gray-400 */
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #9CA3AF transparent;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
    }

    .select2-dropdown {
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray);
        padding: 0;
        min-width: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        left: 0 !important;
        background: #1F2937; /* bg-gray-800 */
    }
    
    .select2-results__options {
        max-height: 200px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--gray-dark) transparent; /* gray-600 */
    }

    .select2-results__options::-webkit-scrollbar {
        width: 6px;
    }

    .select2-results__options::-webkit-scrollbar-track {
        background: transparent;
    }

    .select2-results__options::-webkit-scrollbar-thumb {
        background: var(--gray-dark); /* gray-600 */
        border-radius: 3px;
    }

    .select2-results__option {
        padding: 0.5rem 0.75rem;
        transition: var(--transition);
        white-space: normal;
        word-wrap: break-word;
        line-height: 1.4;
        border-bottom: 1px solid #374151; /* border-gray-700 */
        color: #D1D5DB; /* text-gray-300 */
    }

    .select2-results__option:last-child {
        border-bottom: none;
    }

    .select2-results__option--highlighted {
        background: #374151 !important; /* bg-gray-700 */
        color: #FFF !important;
    }

    .select2-results__option--selected {
        background: #4B5563 !important; /* bg-gray-600 */
        color: #fff !important;
        font-weight: 600;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: var(--secondary-light); /* gray-600 */
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: var(--error);
        font-size: 1.125rem;
        margin-right: 0.5rem;
    }

    .select2-container {
        width: 100% !important;
    }

    /* PERBAIKAN: Modal diubah ke dark mode */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(17, 24, 39, 0.7); /* bg-gray-900/70 */
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        overflow-x: hidden;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background: #1F2937; /* bg-gray-800 */
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        width: 95%;
        max-width: 600px;
        max-height: 85vh;
        overflow-y: auto;
        transform: translateY(20px);
        transition: var(--transition);
        border: 1px solid #374151; /* border-gray-700 */
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .modal-header {
        padding: 1rem;
        border-bottom: 1px solid var(--gray);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark); /* gray-100 */
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: var(--secondary); /* gray-500 */
        transition: var(--transition);
    }

    .modal-close:hover {
        color: var(--error);
    }

    .modal-body {
        padding: 1rem;
    }

    .modal-image {
        width: 100%;
        height: auto;
        max-height: 60vh;
        object-fit: contain;
        border-radius: calc(var(--border-radius) - 0.25rem);
    }

    /* PERBAIKAN: Tabel item diubah ke dark mode */
    .item-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        background: #111827; /* bg-gray-900 */
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray);
    }

    .item-table th,
    .item-table td {
        border-bottom: 1px solid var(--gray);
        padding: 0.5rem;
        vertical-align: middle;
        text-align: left;
    }

    .item-table th {
        background: var(--light); /* gray-800 */
        color: var(--secondary-dark); /* gray-300 */
        font-weight: 600;
        font-size: 0.875rem;
    }

    .item-table td {
        color: var(--dark); /* gray-100 */
    }

    .item-table .input-group {
        margin-bottom: 0.5rem;
    }

    .item-table .input-group input {
        padding: 0.5rem;
        font-size: 0.875rem;
        width: 100%;
    }

    .item-table .select2-container--default .select2-selection--single {
        height: 36px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .item-table .btn-danger {
        padding: 0.5rem;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .item-table .btn-danger i {
        margin: 0;
    }

    .stok-info {
        display: block;
        font-size: 0.75rem;
        color: var(--secondary); /* gray-500 */
        margin-top: 0.25rem;
        text-align: left;
    }

    .income-table td:nth-child(2) .input-group input,
    .income-table td:nth-child(3) .input-group input {
        text-align: center;
        width: 100%;
        min-width: 0;
    }

    .income-table td:nth-child(4) .subtotal-display {
        text-align: right;
    }

    .total-display {
        text-align: right;
        font-size: 1rem;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: var(--light); /* gray-800 */
        border-radius: var(--border-radius);
        border: 1px solid var(--gray);
        color: var(--dark); /* gray-100 */
    }

    /* PERBAIKAN: CSS Responsif diubah ke dark mode */
    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .form-inner {
            padding: 2rem;
        }

        .button-group {
            flex-direction: row;
            justify-content: flex-end;
        }

        .btn {
            width: auto;
        }

        .tab-button {
            flex: 0 1 auto;
        }
    }

    @media (max-width: 767px) {
        .item-table,
        .item-table thead,
        .item-table tbody,
        .item-table tr {
            display: block;
            width: 100%;
        }
        .item-table thead {
            display: none;
        }
        .item-table tr {
            margin-bottom: 1rem;
            border: 1px solid var(--gray);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            background: #1F2937; /* bg-gray-800 */
            padding: 0.5rem;
            max-width: 100%;
        }
        .item-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.25rem !important;
            font-size: 0.85rem;
            border: none;
            position: relative;
            box-sizing: border-box;
            word-break: break-word;
            max-width: 100%;
        }
        .item-table td::before {
            content: attr(data-label);
            flex: 0 0 110px;
            font-weight: 600;
            color: var(--secondary-dark); /* gray-300 */
            margin-right: 0.5rem;
            font-size: 0.85rem;
            text-align: left;
            min-width: 90px;
            max-width: 40vw;
            word-break: break-word;
        }
        .item-table .input-group input,
        .item-table .input-group select {
            width: 100%;
            min-width: 0;
            max-width: 100%;
            font-size: 0.85rem;
            box-sizing: border-box;
        }
        .item-table .btn-danger {
            min-width: 36px;
            padding: 0.5rem !important;
            font-size: 1rem !important;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .subtotal-display {
            font-size: 0.85rem !important;
            text-align: left;
            word-break: break-word;
            color: #FFF;
        }
        .section-title {
            font-size: 1rem;
        }
        .tab-button {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }
    }
    
    /* ... (CSS media query lain tetap sama) ... */
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
                            <span>Buat Pemasukan</span>
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
                                    Tanggal Masuk
                                </label>
                                <input type="date" name="tanggal_struk" id="tanggal_struk" required value="{{ old('tanggal_struk', date('Y-m-d')) }}" style="color-scheme: dark;">
                            </div>

                            <div class="input-group">
                                <label for="status">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Status
                                </label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="progress">Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
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
                                    <th>Detail Barang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="income-items-container">
                                <tr class="item-row" data-item="0">
                                    <td data-label="Detail Barang">
                                        <div class="input-group">
                                            <label>Barang</label>
                                            <select name="items[0][nama]" class="select-barang" required>
                                                <option value="">Pilih Barang</option>
                                                @foreach ($barangList as $barang)
                                                <option value="{{ $barang->kode_barang }}" data-stok="{{ $barang->jumlah }}">
                                                    {{ $barang->nama_barang }} ({{ $barang->kode_barang }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group">
                                            <label>Jumlah</label>
                                            <input type="number" name="items[0][jumlah]" class="jumlah" min="1" value="1" required>
                                        </div>
                                        <div class="input-group">
                                            <label>Harga Satuan</label>
                                            <input type="number" name="items[0][harga]" class="harga" min="0" required placeholder="0">
                                        </div>
                                        <div class="input-group">
                                            <label>Subtotal</label>
                                            <div class="subtotal-display" id="subtotal-0">Rp 0</div>
                                            <input type="hidden" name="items[0][subtotal]" class="subtotal" value="0">
                                        </div>
                                    </td>
                                    <td data-label="Aksi" style="width: 60px;">
                                        <button type="button" onclick="removeIncomeItem(this)" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="text-center mb-4">
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
                            <input type="file" name="foto_struk" id="foto_struk" accept="image/*" class="file-upload-input" onchange="previewUploadedImage(this, 'income')">
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
                                <input type="text" name="nama_spk" id="expense_nama_spk" class="form-input w-full bg-gray-700 text-gray-400 opacity-80 cursor-not-allowed" placeholder="Terisi otomatis" readonly>
                                <small class="text-gray-500">Format: SPK-[DIVISI]-[NIP 2 digit]-[001]</small>
                            </div>

                            <div class="input-group">
                                <label for="expense_nomor_struk">
                                    <i class="fas fa-receipt mr-1"></i>
                                    Nomor Struk
                                </label>
                                <input type="text" name="nomor_struk" id="expense_nomor_struk" value="{{ $nextSpkNumber ?? '' }}" class="form-input w-full bg-gray-700 text-gray-400 placeholder-gray-500 opacity-80 cursor-not-allowed" placeholder="Terisi otomatis" readonly>
                                <small class="text-gray-500">Format: spk/DD/MM/YYXXXXX</small>
                            </div>

                            <div class="input-group">
                                <label for="expense_tanggal">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Tanggal Pengeluaran
                                </label>
                                <input type="date" name="tanggal" id="expense_tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}" style="color-scheme: dark;">
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
                                <input type="text" id="divisi_display" class="form-input w-full bg-gray-700 text-gray-400 placeholder-gray-500" placeholder="Terisi otomatis" readonly>
                            </div>

                            <div class="input-group">
                                <label for="status_pengeluaran">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Status
                                </label>
                                <select name="status" id="status_pengeluaran" class="form-control" required>
                                    <option value="progress">Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
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
                                    <td data-label="Barang">
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
                                    <td data-label="Jumlah">
                                        <div class="input-group">
                                            <input type="number" name="items[0][jumlah]" class="jumlah" min="1" value="1" required>
                                        </div>
                                    </td>
                                    <td data-label="Aksi" style="width: 60px;">
                                        <button type="button" onclick="removeExpenseItem(this)" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="text-center mb-4">
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
</div>

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

{{-- SCRIPT JAVASCRIPT ANDA SAYA BIARKAN UTUH --}}
{{-- Saya hanya menambahkan inisialisasi dark mode untuk modal & select2 --}}
<script>
    let incomeIndex = 1;
    let expenseItemIndex = 1;

    function formatRupiah(angka) {
        if (!angka) return 'Rp 0';
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function calculateOptimalDropdownWidth(selectElement) {
        const $select = $(selectElement);
        const options = $select.find('option');
        let maxLength = 0;
        let longestText = '';

        options.each(function() {
            const text = $(this).text().trim();
            if (text.length > maxLength && text !== '') {
                maxLength = text.length;
                longestText = text;
            }
        });

        const $temp = $('<span>').css({
            'font-family': $('.select2-dropdown').css('font-family') || 'Plus Jakarta Sans, sans-serif',
            'font-size': $('.select2-dropdown').css('font-size') || '0.875rem',
            'font-weight': 'normal',
            'position': 'absolute',
            'visibility': 'hidden',
            'white-space': 'nowrap',
            'padding': '0.5rem 0.75rem'
        }).text(longestText).appendTo('body');

        const textWidth = $temp.outerWidth();
        $temp.remove();

        const minWidth = 200;
        const maxWidth = Math.min(600, $(window).width() * 0.95);
        const optimalWidth = Math.max(minWidth, Math.min(textWidth + 40, maxWidth));

        return optimalWidth;
    }

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

    function updateStokExpense(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const stokAsli = parseInt(selectedOption.getAttribute('data-stok')) || 0;
        const row = selectElement.closest('tr');
        const jumlahInput = row.querySelector('.jumlah');
        const stokInfo = row.querySelector('.stok-info');
        
        // Set max attribute
        jumlahInput.max = stokAsli;

        const jumlah = parseInt(jumlahInput?.value || 0);
        const sisa = stokAsli - jumlah;

        stokInfo.textContent = `Stok: ${sisa >= 0 ? sisa : 0}`;

        // Hapus event listener lama jika ada untuk menghindari duplikasi
        if (jumlahInput.stockUpdateHandler) {
            jumlahInput.removeEventListener('input', jumlahInput.stockUpdateHandler);
        }

        jumlahInput.stockUpdateHandler = function() {
            const inputJumlah = parseInt(jumlahInput.value) || 0;
            
            if (inputJumlah > stokAsli) {
                Swal.fire({ title: 'Stok Tidak Cukup', text: `Stok tersedia: ${stokAsli}\nJumlah diminta: ${inputJumlah}`, icon: 'error', background: '#1F2937', color: '#F9FAFB' });
                jumlahInput.value = stokAsli;
            }
            
            const sisaBaru = stokAsli - parseInt(jumlahInput.value);
            stokInfo.textContent = `Stok: ${sisaBaru >= 0 ? sisaBaru : 0}`;
        };

        jumlahInput.addEventListener('input', jumlahInput.stockUpdateHandler);
        
        // Trigger sekali untuk set nilai awal
        jumlahInput.stockUpdateHandler();
    }

    function updateIncomeStok(selectElement) {
        // Fungsi ini tampaknya tidak memiliki .stok-info, jadi kita biarkan
    }

    function addIncomeItem() {
        const container = document.getElementById('income-items-container');
        if (!container) return;

        const oldRow = container.querySelector('.item-row');
        const newRow = oldRow.cloneNode(true);

        newRow.innerHTML = newRow.innerHTML.replace(/id="subtotal-0"/g, `id="subtotal-${incomeIndex}"`);
        const regex = /\[0\]/g;
        newRow.innerHTML = newRow.innerHTML.replace(regex, `[${incomeIndex}]`);
        newRow.setAttribute('data-item', incomeIndex);

        const inputs = newRow.querySelectorAll('input');
        inputs.forEach(input => {
            if (input.type === 'number') {
                input.value = input.classList.contains('jumlah') ? 1 : 0;
            }
            if (input.type === 'hidden') {
                input.value = 0;
            }
        });

        const select = newRow.querySelector('.select-barang');
        select.selectedIndex = 0;
        $(select).next('.select2-container').remove();
        initializeSelect2ForElement(select);

        select.addEventListener('change', function() {
            updateIncomeStok(this);
        });

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
        } else {
            Swal.fire({ title: 'Aksi Diblokir', text: 'Minimal harus ada satu item barang.', icon: 'warning', background: '#1F2937', color: '#F9FAFB' });
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

    function addExpenseItem() {
        const container = $('#expense-items-container');
        if (!container.length) return;

        const oldRow = container.find('.item-row').first();
        const newRow = oldRow.clone();

        newRow.attr('data-item', expenseItemIndex);
        newRow.html(newRow.html().replace(/\[0\]/g, `[${expenseItemIndex}]`));

        newRow.find('input').each(function() {
            if (this.type === 'number') {
                this.value = this.classList.contains('jumlah') ? 1 : 0;
            }
        });

        const select = newRow.find('.select-barang');
        select.val('');
        select.next('.select2-container').remove();
        initializeSelect2ForElement(select[0]);
        newRow.find('.stok-info').text('Stok: -');
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
        } else {
            Swal.fire({ title: 'Aksi Diblokir', text: 'Minimal harus ada satu item barang.', icon: 'warning', background: '#1F2937', color: '#F9FAFB' });
        }
    }

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
                const dropdown = $element.data('select2').$dropdown;
                dropdown.css({
                    'width': optimalWidth + 'px',
                    'min-width': '200px',
                    'max-width': Math.min(600, $(window).width() * 0.95) + 'px'
                });
                // PERBAIKAN: Tambahkan class dark mode ke dropdown
                dropdown.addClass('select2-dropdown-dark');
            });
        } else {
            $element.select2({
                placeholder: "Pilih...",
                width: '100%',
                dropdownAutoWidth: true,
                closeOnSelect: true,
                dropdownParent: $('.form-card'),
                dropdownCssClass: 'select2-dropdown-dark' // Tambahkan class dark mode
            });
        }
    }

    function initSelect2() {
        $('.select-barang').each(function() {
            initializeSelect2ForElement(this);
        });

        if ($('#pegawai_id').length) {
            initializeSelect2ForElement($('#pegawai_id')[0]);
        }
        if ($('#status').length) {
            initializeSelect2ForElement($('#status')[0]);
        }
        if ($('#status_pengeluaran').length) {
            initializeSelect2ForElement($('#status_pengeluaran')[0]);
        }
    }

    $(document).ready(function() {
        initSelect2();

        $('.tab-button').on('click', function(e) {
            e.preventDefault();
            const tabId = $(this).data('tab');
            $('.tab-button').removeClass('active');
            $(this).addClass('active');
            $('.tab-content').removeClass('active');
            $('#' + tabId).addClass('active');
        });

        if ($('#income-items-container .item-row').length) {
            $('#income-items-container .item-row').each(function() {
                const row = $(this);
                row.find('.jumlah, .harga').on('input', function() {
                    updateIncomeSubtotal(row);
                });
                // Inisialisasi subtotal awal
                updateIncomeSubtotal(row);
            });
        }
        
        // Inisialisasi stok awal untuk baris pertama
        updateStokExpense($('#expense-items-container .item-row[data-item="0"] .select-barang')[0]);

        $('#imageModal').click(function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        if ($('#pegawai_id').length) {
            $('#pegawai_id').on('change', function() {
                const pegawaiId = $(this).val();
                $('#divisi_display').val('');
                $('#expense_nama_spk').val('');
                $('#expense_nomor_struk').val('');

                if (pegawaiId) {
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

        $(document).on('select2:close', '.select-barang, #pegawai_id, #status', function() {
            $(this).blur();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.select2-container, .select2-dropdown').length) {
                $('.select2-container--open').find('.select2-selection').trigger('blur');
            }
        });

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