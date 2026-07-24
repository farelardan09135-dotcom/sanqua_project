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

        {{-- Sidebar Kasir --}}
        <aside class="w-64 shrink-0 bg-linear-to-b from-blue-600 to-sky-500 flex flex-col shadow-2xl">

            <div class="h-20 flex items-center gap-3 px-6 border-b border-white/20">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-white font-bold text-sm tracking-tight truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-blue-100/80 font-medium uppercase tracking-wide">Kasir</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1.5">
                <p class="px-4 text-[11px] font-semibold text-blue-300/70 uppercase tracking-wider mb-2">Menu</p>

                <a href="{{ route('kasir.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('kasir.index', 'kasir.detail') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm">Kasir</span>
                </a>

                <a href="{{ route('kasir.riwayat') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('kasir.riwayat') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">Riwayat</span>
                </a>

                <a href="{{ route('kasir.cek-barang') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('kasir.cek-barang') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="text-sm">Cek Barang</span>
                </a>

                <a href="{{ route('kasir.setting') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('kasir.setting') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm">Setting</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/20">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-200 hover:text-white transition-colors duration-200">
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