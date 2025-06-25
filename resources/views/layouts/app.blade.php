<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="bg-slate-800 text-white shadow-lg transition-all duration-300" :class="sidebarOpen ? 'w-64' : 'w-20'">
        <!-- Logo -->
        <div class="flex items-center p-4 border-b border-slate-700" :class="sidebarOpen ? '' : 'justify-center'">
            <div class="flex items-center gap-3" :class="sidebarOpen ? '' : 'justify-center w-full'">
                <div class="p-2 bg-blue-600 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold transition-opacity duration-300" x-show="sidebarOpen">Inventaris</h1>
            </div>
        </div>

        <!-- Menu -->
        <nav class="py-4">
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-slate-700 transition-colors rounded-lg mx-2">
                        <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" class="transition-opacity duration-300">Dashboard</span>
                    </a>
                </li>

                <!-- Daftar Struk -->
                <li>
                    <a href="{{ route('struks.index') }}" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-slate-700 transition-colors rounded-lg mx-2">
                        <div class="p-1.5 bg-green-100 text-green-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" class="transition-opacity duration-300">Daftar Struk</span>
                    </a>
                </li>

                <!-- Tambah Struk -->
                <li>
                    <a href="{{ route('struks.create') }}" class="flex items-center gap-3 px-4 py-3 text-white hover:bg-slate-700 transition-colors rounded-lg mx-2">
                        <div class="p-1.5 bg-amber-100 text-amber-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <span x-show="sidebarOpen" class="transition-opacity duration-300">Tambah Struk</span>
                    </a>
                </li>

                <!-- Master Section -->
                <li x-data="{ open: false }">
                    <button @click="sidebarOpen = true; open = !open"
                            class="flex items-center justify-between gap-3 px-4 py-3 text-white hover:bg-slate-700 transition-colors rounded-lg mx-2 w-full">
                        <div class="flex items-center gap-3">
                            <div class="p-1.5 bg-purple-100 text-purple-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            <span x-show="sidebarOpen" class="transition-opacity duration-300">Master</span>
                        </div>
                        <div class="pr-2">
                            <svg x-show="sidebarOpen"
                                 :class="{ 'rotate-180': open }"
                                 class="w-4 h-4 text-white transform transition-transform duration-300"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Submenu -->
                    <div x-show="open" x-cloak class="ml-10 mt-1 space-y-1" :class="sidebarOpen ? 'block' : 'hidden'">
                        <a href="{{ route('master-barang.index') }}" class="block px-3 py-2 text-sm text-white hover:bg-slate-600 rounded">Master Barang</a>
                        <a href="#" class="block px-3 py-2 text-sm text-white hover:bg-slate-600 rounded">Master Pegawai</a>
                    </div>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-slate-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" x-show="sidebarOpen"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" x-show="!sidebarOpen"></path>
                        </svg>
                    </button>
                    <h2 class="text-xl font-semibold text-slate-800">Inventaris Barang</h2>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold uppercase">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="font-medium">
                                {{ explode(' ', trim(Auth::user()->name))[0] }}
                            </span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span>Logout</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline mr-3">Login</a>
                    <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register</a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6">
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
