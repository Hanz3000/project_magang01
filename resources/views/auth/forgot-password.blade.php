<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <title>Lupa Password - Inventaris Barang PDAM Surabaya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Paksa background gelap untuk cegah "white flash" */
        html, body {
            background-color: #030712; /* bg-gray-950 */
        }
        body {
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }

        /* Style SweetAlert (Jika Anda menggunakannya di sini) */
        .swal2-popup {
            background: #1F2937 !important; /* bg-gray-800 */
            color: #F9FAFB !important; /* text-gray-100 */
        }
        .swal2-title {
            color: #F9FAFB !important; /* text-gray-100 */
        }
        .swal2-html-container {
            color: #D1D5DB !important; /* text-gray-300 */
        }
        
        /* Style scrollbar dark mode */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1f2937; /* bg-gray-800 */
        }
        ::-webkit-scrollbar-thumb {
            background: #4B5563; /* bg-gray-600 */
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6B7280; /* bg-gray-500 */
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 bg-gray-950 text-gray-200">
    
    {{-- PERBAIKAN: Menggunakan 'bg-gray-900' dan 'border-gray-700' --}}
    <div class="w-full max-w-md bg-gray-900 rounded-2xl shadow-2xl p-8 sm:p-10 text-center border border-gray-700">
        
        <div class="flex items-center justify-center gap-3 mb-8">
            {{-- PERBAIKAN: Menggunakan 'bg-gray-800' --}}
            <div class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center border border-gray-700">
                <i class="fas fa-key text-gray-400 text-lg"></i> 
            </div>
            <span class="text-xl font-semibold text-gray-400">Reset Password</span>
        </div>

        <h1 class="text-3xl font-bold text-white mb-4 tracking-tight">Lupa Kata Sandi?</h1>
        
        <p class="text-gray-400 mb-8 leading-relaxed text-[15px] max-w-sm mx-auto">
            Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang kata sandi.
        </p>

        @if (session('status'))
            {{-- PERBAIKAN: Notifikasi disesuaikan dengan tema --}}
            <div class="bg-green-900 border border-green-700 text-green-300 p-4 rounded-lg mb-6 text-sm text-left">
                {{ session('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-300 p-4 rounded-lg mb-6 text-sm text-left">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6 text-left">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                <div class="relative"> 
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-500 text-sm"></i>
                    </div>
                    {{-- PERBAIKAN: Input disesuaikan dengan tema --}}
                    <input type="email" name="email" id="email" required
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder:text-gray-500 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" 
                        placeholder="nama@email.com" 
                        value="{{ old('email') }}" autofocus> 
               </div>
            </div>

            {{-- PERBAIKAN: Tombol disesuaikan dengan tema --}}
            <button type="submit"
                class="w-full bg-gray-200 text-gray-900 py-3 px-4 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-200 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 focus:ring-offset-gray-900 mt-8">
                Kirim Link Reset
            </button>
        </form>

        <div class="text-center text-sm text-gray-400 mt-8 pt-6 border-t border-gray-700">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 hover:text-white hover:underline transition-colors duration-150 font-medium">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Kembali ke login</span>
            </a>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>