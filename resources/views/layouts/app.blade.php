<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-gradient: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            --glow-primary: rgba(102, 126, 234, 0.4);
            --glow-secondary: rgba(118, 75, 162, 0.3);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .sidebar-gradient {
            background: var(--sidebar-gradient);
            position: relative;
            overflow: hidden;
        }

        .sidebar-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg,
                    transparent 30%,
                    rgba(255, 255, 255, 0.05) 50%,
                    transparent 70%);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .hover-glow {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .hover-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .hover-glow:hover::before {
            transform: translateX(100%);
        }

        .hover-glow:hover {
            box-shadow:
                0 0 30px var(--glow-primary),
                0 10px 25px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transform: translateY(-2px) scale(1.02);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .menu-item {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
            transition: left 0.5s ease;
        }

        .menu-item:hover::after {
            left: 100%;
        }

        .menu-item:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .icon-glow {
            filter: drop-shadow(0 0 8px currentColor);
            transition: all 0.3s ease;
        }

        .menu-item:hover .icon-glow {
            filter: drop-shadow(0 0 12px currentColor) brightness(1.2);
            transform: scale(1.1) rotate(5deg);
        }

        .slide-in-right {
            animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%) scale(0.9);
                opacity: 0;
            }

            to {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logo-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow:
                0 0 20px rgba(102, 126, 234, 0.4),
                0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow:
                0 0 30px rgba(102, 126, 234, 0.6),
                0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .toggle-btn {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .toggle-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.3) 0%, transparent 70%);
            transition: all 0.3s ease;
            transform: translate(-50%, -50%);
        }

        .toggle-btn:hover::before {
            width: 100px;
            height: 100px;
        }

        .toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .submenu-item {
            transition: all 0.3s ease;
            position: relative;
        }

        .submenu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 3px;
            height: 0;
            background: linear-gradient(to bottom, #667eea, #764ba2);
            transition: height 0.3s ease;
            transform: translateY(-50%);
        }

        .submenu-item:hover::before {
            height: 100%;
        }

        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 1rem;
            transform: translateX(5px);
        }

        .main-content {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: float 6s infinite linear;
        }

        .particle:nth-child(odd) {
            animation-delay: -3s;
            background: rgba(102, 126, 234, 0.4);
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        @keyframes spin-reverse {
            from {
                transform: rotate(360deg);
            }

            to {
                transform: rotate(0deg);
            }
        }

        @keyframes progress {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }

        .animate-spin-reverse {
            animation: spin-reverse 1s linear infinite;
        }

        .animate-progress {
            animation: progress 3s ease-in-out infinite;
        }

        .content-area {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .content-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.5), transparent);
        }

        .auth-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .auth-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .auth-button:hover::before {
            left: 100%;
        }

        .auth-button:hover {
            transform: translateY(-1px);
            text-shadow: 0 0 8px currentColor;
        }

        .user-avatar {
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
        }

        .dropdown-item {
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1), transparent);
            padding-left: 1.25rem;
        }
    </style>
</head>

<body class="min-h-screen"
    x-data="{ sidebarOpen: true, showWelcome: {{ session('show_welcome', false) ? 'true' : 'false' }} }"
    x-init="if(showWelcome){ setTimeout(() => showWelcome = false, 3000); }">
    <!-- Welcome Animation -->
    <div x-show="showWelcome" x-transition:leave="transition ease-in duration-300"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gradient-to-br from-indigo-600 to-purple-700">
        <div class="text-center text-white space-y-6">
            <div class="mx-auto w-24 h-24 border-4 border-white border-t-transparent rounded-full animate-spin flex items-center justify-center">
                <div class="w-16 h-16 border-4 border-blue-300 border-b-transparent rounded-full animate-spin-reverse"></div>
            </div>
            <div class="space-y-2">
                <h2 class="text-3xl font-bold tracking-tight text-white/90">Selamat Datang</h2>
                <p class="text-indigo-200 font-medium">Sistem Inventaris Barang</p>
            </div>
            <div class="pt-4">
                <div class="h-1.5 w-40 mx-auto bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full animate-progress"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- Hapus session setelah welcome tampil --}}
    @if(session('show_welcome'))
    {{ session()->forget('show_welcome') }}
    @endif

    <!-- Floating Particles -->
    <div class="floating-particles">
        <div class="particle" style="left: 10%; animation-duration: 8s;"></div>
        <div class="particle" style="left: 20%; animation-duration: 6s; animation-delay: -2s;"></div>
        <div class="particle" style="left: 30%; animation-duration: 10s; animation-delay: -1s;"></div>
        <div class="particle" style="left: 40%; animation-duration: 7s; animation-delay: -3s;"></div>
        <div class="particle" style="left: 50%; animation-duration: 9s;"></div>
        <div class="particle" style="left: 60%; animation-duration: 8s; animation-delay: -1.5s;"></div>
        <div class="particle" style="left: 70%; animation-duration: 6s; animation-delay: -2.5s;"></div>
        <div class="particle" style="left: 80%; animation-duration: 11s;"></div>
        <div class="particle" style="left: 90%; animation-duration: 7s; animation-delay: -4s;"></div>
    </div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar-gradient text-white shadow-2xl transition-all duration-500 ease-in-out relative"
            :class="sidebarOpen ? 'w-72' : 'w-20'">
            <!-- Header Logo -->

            <div class="flex items-center p-6 border-b border-white/20">
                <div class="flex items-center gap-4">
                    <div class="logo-container p-3 rounded-xl hover:rotate-6 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white icon-glow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div x-show="sidebarOpen" x-transition class="overflow-hidden">
                        <h1 class="text-xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                            <span class="block animate-slide-up" style="animation-delay: 0.1s;">Inventaris</span>
                            <span class="block text-sm font-medium text-white/80 animate-slide-up" style="animation-delay: 0.2s;">Sistem Manajemen</span>
                        </h1>
                    </div>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <nav class="px-4 py-6">
                <ul class="space-y-4 pb-24"> {{-- Extra padding for logout spacing --}}
                    <!-- Menu Items -->
                    <li class="fade-in" style="animation-delay: 0.1s;">
                        <a href="{{ url('/dashboard') }}"
                            class="menu-item group flex items-center gap-4 px-4 py-4 rounded-xl hover-glow transition-all duration-300">
                            <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg">
                                <svg class="w-5 h-5 text-white icon-glow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="font-medium">Dashboard</span>
                        </a>
                    </li>

                    <li class="fade-in" style="animation-delay: 0.2s;">
                        <a href="{{ route('struks.index') }}"
                            class="menu-item group flex items-center gap-4 px-4 py-4 rounded-xl hover-glow transition-all duration-300">
                            <div class="p-2 bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg">
                                <svg class="w-5 h-5 text-white icon-glow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="font-medium">Daftar Struk</span>
                        </a>
                    </li>

                    <li class="fade-in" style="animation-delay: 0.3s;">
                        <a href="{{ route('struks.create') }}"
                            class="menu-item group flex items-center gap-4 px-4 py-4 rounded-xl hover-glow transition-all duration-300">
                            <div class="p-2 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg shadow-lg">
                                <svg class="w-5 h-5 text-white icon-glow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <span x-show="sidebarOpen" x-transition class="font-medium">Tambah Struk</span>
                        </a>
                    </li>

                    <li x-data="{ open: false }" class="fade-in" style="animation-delay: 0.4s;">
                        <button @click="sidebarOpen = true; open = !open"
                            class="menu-item group flex items-center justify-between w-full gap-4 px-4 py-4 rounded-xl hover-glow transition-all duration-300">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg">
                                    <svg class="w-5 h-5 text-white icon-glow" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                </div>
                                <span x-show="sidebarOpen" x-transition class="font-medium">Master</span>
                            </div>
                            <svg x-show="sidebarOpen" :class="{ 'rotate-180': open }"
                                class="w-4 h-4 text-white transform transition-all duration-300 icon-glow"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open && sidebarOpen"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="ml-6 mt-3 space-y-2 text-sm">
                            <a href="{{ route('master-barang.index') }}"
                                class="submenu-item block px-4 py-3 text-white rounded-lg font-medium">Master Barang</a>
                            <a href="{{ route('pegawai.index') }}"
                                class="submenu-item block px-4 py-3 text-white rounded-lg font-medium">Master Pegawai</a>
                        </div>
                    </li>
                </ul>


            </nav>
        </aside>


        <!-- Main Content -->
        <main class="flex-1 h-screen overflow-y-auto main-content">

            <header class="header-glass px-6 py-4 shadow-lg sticky top-0 z-40">

                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="toggle-btn p-3 rounded-xl shadow-md relative z-10">
                            <svg class="w-6 h-6 text-slate-600 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                        </button>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                            Inventaris Barang
                        </h2>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                        <!-- User Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                                <div class="relative">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                                </div>
                                <span class="hidden md:inline-block font-medium text-slate-700">{{ Auth::user()->name }}</span>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-slate-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden">
                                <div class="py-1">
                                    <div class="px-4 py-3 border-b border-slate-100">
                                        <p class="text-sm font-medium text-slate-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}"
                            class="auth-button px-6 py-2 text-blue-600 hover:text-blue-700 font-semibold rounded-lg">
                            Login
                        </a>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-6 min-h-full">
                <div class="content-area p-6 fade-in">
                    @yield('content')
                </div>






</html>
</body>
</div>
</main>
</div>
</div>
</main>
</div>
</body>

</html>