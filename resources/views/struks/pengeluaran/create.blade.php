@extends('layouts.app')

@section('content')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .form-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .form-inner {
            padding: 2.5rem;
        }

        /* === FORM ELEMENTS === */
        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: var(--font-xl);
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #edf2f7;
        }

        .section-title i {
            font-size: var(--font-lg);
            color: var(--primary);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-group label {
            position: absolute;
            top: -0.5rem;
            left: 0.75rem;
            background: white;
            padding: 0 0.5rem;
            font-size: var(--font-sm);
            font-weight: 600;
            color: #4a5568;
            z-index: 10;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            font-size: var(--font-base);
            background: var(--bg-light);
            transition: all 0.2s ease;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1);
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
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: var(--font-base);
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
        }

        .btn-secondary:hover {
            background: var(--secondary-hover);
        }

        /* === ALERTS === */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            font-size: var(--font-base);
        }

        .alert-success {
            background: #f0fff4;
            color: #22543d;
            border-left-color: var(--success);
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border-left-color: var(--error);
        }

        .alert ul {
            margin-top: 0.5rem;
            padding-left: 1.5rem;
        }

        /* === SELECT2 CUSTOMIZATION === */
        .select2-container--default .select2-selection--single {
            border: 1px solid var(--border) !important;
            border-radius: 0.75rem !important;
            padding: 0.75rem !important;
            height: auto !important;
            background: var(--bg-light) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 0.75rem !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1) !important;
        }

        /* === RESPONSIVE ADJUSTMENTS === */
        @media (max-width: 768px) {
            .form-inner {
                padding: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column-reverse;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
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

                <form action="{{ route('pengeluarans.store') }}" method="POST">
                    @csrf

                    <div class="section-title">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Buat Pengeluaran dari Pemasukan</span>
                    </div>

                    <div class="form-grid">
                        {{-- Struk Selection --}}
                        <div class="input-group">
                            <label for="struk_id">
                                <i class="fas fa-receipt mr-1"></i>
                                Pilih Data Pemasukan
                            </label>
                            <select name="struk_id" id="struk_id" class="select-struk" required>
                                <option value="">-- Pilih Struk --</option>
                                @foreach ($struks as $struk)
                                    <option value="{{ $struk->id }}" data-total="{{ $struk->total_harga }}">
                                        {{ $struk->nama_toko }} - No: {{ $struk->nomor_struk }} - Total:
                                        Rp{{ number_format($struk->total_harga, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pegawai Selection --}}
                        <div class="input-group">
                            <label for="pegawai_id">
                                <i class="fas fa-user-tie mr-1"></i>
                                Pegawai
                            </label>
                            <select name="pegawai_id" id="pegawai_id" class="select-pegawai" required>
                                <option value="">-- Pilih Pegawai --</option>
                                @foreach ($pegawais as $pegawai)
                                    <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date Input --}}
                        <div class="input-group">
                            <label for="tanggal">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Tanggal Pengeluaran
                            </label>
                            <input type="date" name="tanggal" id="tanggal" required
                                value="{{ old('tanggal', date('Y-m-d')) }}">
                        </div>

                        {{-- Total Display --}}
                        <div class="input-group">
                            <label for="total_pengeluaran">
                                <i class="fas fa-coins mr-1"></i>
                                Total Pemasukan
                            </label>
                            <input type="text" id="total_pengeluaran" readonly>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="button-group">
                        <a href="{{ route('pengeluarans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select-struk').select2({
                placeholder: "Cari atau pilih struk...",
                width: '100%',
                language: {
                    noResults: function() {
                        return "Struk tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });

            $('.select-pegawai').select2({
                placeholder: "Cari atau pilih pegawai...",
                width: '100%',
                language: {
                    noResults: function() {
                        return "Pegawai tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });

            // Update total when struk is selected
            $('#struk_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const total = selectedOption.data('total');

                if (total) {
                    $('#total_pengeluaran').val(formatRupiah(total));
                } else {
                    $('#total_pengeluaran').val('');
                }
            });
        });

        // Format currency
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    </script>
@endsection
