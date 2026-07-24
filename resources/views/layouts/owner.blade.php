<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen w-full bg-linear-to-b from-sky-200 via-sky-100 to-blue-50 flex">

        {{-- Sidebar Owner: warna beda dari Admin/Kasir biar gampang dibedain --}}
        <aside class="w-64 shrink-0 bg-linear-to-b from-blue-600 to-sky-500 flex flex-col shadow-2xl">

            <div class="h-20 flex items-center gap-3 px-6 border-b border-white/20">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0a2 2 0 002 2h2a2 2 0 002-2v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <span class="text-white font-bold text-lg tracking-tight">Owner Panel</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1.5">
                <p class="px-4 text-[11px] font-semibold text-indigo-300/70 uppercase tracking-wider mb-2">Menu</p>

                <a href="{{ route('owner.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-white text-indigo-800 shadow-md font-semibold' : 'text-indigo-100 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                <a href="{{ route('owner.penjualan') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('owner.penjualan*') ? 'bg-white text-indigo-800 shadow-md font-semibold' : 'text-indigo-100 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0a2 2 0 002 2h2a2 2 0 002-2v-3a2 2 0 00-2-2h-2a2 2 0 00-2 2z" />
                    </svg>
                    <span class="text-sm">Laporan Penjualan</span>
                </a>

                <a href="{{ route('owner.stok') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('owner.stok') ? 'bg-white text-indigo-800 shadow-md font-semibold' : 'text-indigo-100 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="text-sm">Laporan Stok</span>
                </a>

                <a href="{{ route('owner.pembelian') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('owner.pembelian') ? 'bg-white text-indigo-800 shadow-md font-semibold' : 'text-indigo-100 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="text-sm">Laporan Pembelian</span>
                </a>

                <a href="{{ route('owner.forecast') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('owner.forecast') ? 'bg-white text-indigo-800 shadow-md font-semibold' : 'text-indigo-100 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span class="text-sm">Forecast</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/20">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-indigo-100 hover:text-white transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            {{ $slot }}
        </main>

    </div>
</body>
</html>