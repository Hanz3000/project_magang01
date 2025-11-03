<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<title>Masuk - Inventaris Barang PDAM Surabaya</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
font-family: 'Inter', sans-serif;
background-color: #111827;
color: #E5E7EB;
overflow: hidden; /* Mencegah scroll di body */
}

:root {
--dark-bg-main: #111827;
--dark-bg-container: #1F2937;
--dark-bg-card-left: #374151;
--dark-bg-card-right: #1F2937;
--dark-bg-input: #374151;
--text-light: #F9FAFB;
--text-medium: #D1D5DB;
--text-dark: #9CA3AF;
--border-gray: #4B5563;
--accent-gray-light: #9CA3AF;
--accent-gray-hover: #6B7280;
--focus-ring-gray: rgba(156, 163, 175, 0.4);
--highlight-gray: #E5E7EB;
--error-bg-dark: rgba(220, 38, 38, 0.2);
--error-text-dark: #f87171;
}

.bg-main { background-color: var(--dark-bg-main); }
.bg-container-dark { background-color: var(--dark-bg-container); }
.bg-card-left { background-color: var(--dark-bg-card-left); }
.bg-card-right { background-color: var(--dark-bg-card-right); }
.bg-input-dark { background-color: var(--dark-bg-input); }
.text-light { color: var(--text-light); }
.text-medium { color: var(--text-medium); }
.text-dark { color: var(--text-dark); }
.border-gray { border-color: var(--border-gray); }
.bg-accent-light { background-color: var(--accent-gray-light); }
.hover\:bg-accent-hover:hover { background-color: var(--accent-gray-hover); }
.text-accent-light { color: var(--accent-gray-light); }
.hover\:text-light:hover { color: var(--text-light); }
.focus\:ring-gray:focus {
--tw-ring-color: var(--focus-ring-gray);
border-color: var(--accent-gray-light);
}
.text-highlight-gray { color: var(--highlight-gray); }

@keyframes fadeInSlideUp {
from { opacity: 0; transform: translateY(10px); }
to { opacity: 1; transform: translateY(0); }
}
@keyframes scaleIn {
from { transform: scale(0.98); opacity: 0; }
to { transform: scale(1); opacity: 1; }
}
@keyframes subtleBgShift {
0% { background-color: #374151; }
50% { background-color: #404a5a; }
100% { background-color: #374151; }
}
@keyframes slowRotate {
from { transform: rotate(-10deg); }
to { transform: rotate(10deg); }
}

.animate-fadeInSlideUp { animation: fadeInSlideUp 0.6s ease-out forwards; }
.animate-scaleIn { animation: scaleIn 0.5s ease-out forwards; }

.input-field {
background-color: var(--dark-bg-input);
color: var(--text-light);
border: 1px solid var(--border-gray);
transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
}
.input-field::placeholder { color: var(--text-dark); }
.input-field:focus {
border-color: var(--accent-gray-light);
box-shadow: 0 0 0 2px var(--focus-ring-gray);
background-color: #4B5563;
outline: none;
}

.left-col-bg {
animation: subtleBgShift 15s ease infinite alternate;
}

.floating-watermark {
position: absolute;
bottom: -50px;
right: -50px;
opacity: 0.03;
font-size: 18rem;
color: var(--text-dark);
z-index: 0;
animation: slowRotate 25s linear infinite alternate;
}

/* Container yang fit dengan viewport */
.login-container {
height: 100vh;
display: flex;
align-items: center;
justify-content: center;
padding: 1rem;
}

/* Responsive height adjustments */
.login-card {
max-height: 95vh;
height: auto;
}

/* Compact spacing untuk mobile */
@media (max-height: 700px) {
.login-card {
max-height: 98vh;
}
.compact-spacing {
padding: 1rem !important;
}
.compact-title {
font-size: 1.5rem !important;
margin-bottom: 0.5rem !important;
}
.compact-form {
margin-top: 1rem !important;
margin-bottom: 1rem !important;
}
}

[x-cloak] { display: none !important; }
</style>
</head>

<body class="bg-main">

    <div class="login-container">
        <div class="relative w-full max-w-5xl bg-container-dark rounded-xl shadow-2xl overflow-hidden flex flex-col lg:flex-row login-card animate-scaleIn">

            <i class="fas fa-box floating-watermark hidden lg:block"></i>
            
            <div class="relative lg:w-1/2 p-6 lg:p-10 compact-spacing flex flex-col justify-center text-light bg-card-left overflow-hidden left-col-bg">
                <div class="relative z-10">
                    <div class="flex items-center mb-4 animate-fadeInSlideUp" style="animation-delay: 0.1s;">
                        <div class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                             <i class="fas fa-box text-gray-300 text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-light">Inventaris Barang</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight mb-3 tracking-tight animate-fadeInSlideUp compact-title" style="animation-delay: 0.2s;">
                        Manajemen Aset <span class="text-highlight-gray">Efisien</span>
                    </h1>
                    <p class="text-base text-medium animate-fadeInSlideUp" style="animation-delay: 0.3s;">
                        Masuk untuk mengelola stok dan aset pergudangan PDAM Surabaya dengan mudah dan akurat.
                    </p>
                </div>
            </div>

            <div class="lg:w-1/2 p-6 lg:p-10 compact-spacing flex flex-col justify-center bg-card-right relative z-10">
                <div class="text-center mb-6 compact-form animate-fadeInSlideUp" style="animation-delay: 0.4s;">
                    <h2 class="text-2xl font-bold text-light mb-2">Selamat Datang Kembali</h2>
                    <p class="text-medium text-sm">Masuk dengan NIP Anda</p>
                </div>

                {{-- PERBAIKAN: Menampilkan error Laravel jika ada --}}
                @if ($errors->any())
                <div class="bg-opacity-20 bg-red-500 border border-red-500 text-error-text-dark p-3 rounded mb-4 text-sm animate-fadeInSlideUp" style="animation-delay: 0.5s;">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- PERBAIKAN: action diubah ke route('login') dan @csrf ditambahkan --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div class="animate-fadeInSlideUp" style="animation-delay: 0.6s;">
                        <label for="nip" class="block text-sm font-medium text-medium mb-1.5">NIP</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-dark"></i>
                            </div>
                            <input type="text" id="nip" name="nip"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg input-field focus:ring-gray"
                                placeholder="Masukkan NIP Anda (8 digit)" value="{{ old('nip') }}" required autofocus
                                pattern="\d{8}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)">
                        </div>
                    </div>

                    <div class="animate-fadeInSlideUp" style="animation-delay: 0.7s;">
                        <label for="password" class="block text-sm font-medium text-medium mb-1.5">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-dark"></i>
                            </div>
                            <input type="password" id="password" name="password"
                                class="w-full pl-10 pr-10 py-2.5 rounded-lg input-field focus:ring-gray"
                                placeholder="••••••••" required>
                            <button type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-dark hover:text-medium transition-colors duration-150"
                                id="togglePassword">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm animate-fadeInSlideUp" style="animation-delay: 0.8s;">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember"
                                class="h-4 w-4 text-accent-light border-gray rounded focus:ring-gray bg-card focus:ring-opacity-50 focus:ring-offset-0">
                            <span class="ml-2 text-medium">Ingat saya</span>
                        </label>
                        {{-- PERBAIKAN: href diubah ke route('password.request') --}}
                        <a href="{{ route('password.request') }}"
                            class="text-accent-light hover:text-light font-medium transition-colors duration-150">Lupa kata sandi?</a>
                    </div>

                    <button type="submit"
                        class="w-full bg-accent-light text-light py-2.5 px-4 rounded-lg font-semibold hover:bg-accent-hover transition-all duration-200 shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-light focus:ring-offset-gray-800 animate-fadeInSlideUp"
                        style="animation-delay: 0.9s;">
                        Masuk
                        <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                    </button>
                </form>

                <div class="text-center text-xs text-dark mt-6 animate-fadeInSlideUp" style="animation-delay: 1s;">
                    <p>NIP & Kata Sandi diberikan oleh Admin. Hubungi Kepegawaian jika ada kendala.</p>
                    <p class="mt-2">&copy; {{ date('Y') }} PDAM Surya Sembada Kota Surabaya</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Toggle password visibility
        const passwordInput = document.querySelector('#password');
        const toggleButton = document.querySelector('#togglePassword');
        const toggleIcon = document.querySelector('#toggleIcon');

        if (passwordInput && toggleButton) {
            toggleButton.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle icon
                if (type === 'text') {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            });
        }
    </script>
</body>

</html>