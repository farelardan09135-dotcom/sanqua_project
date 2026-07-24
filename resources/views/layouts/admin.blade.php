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

        <!-- Sidebar -->
        <aside class="w-64 shrink-0 bg-linear-to-b from-blue-600 to-sky-500 flex flex-col shadow-xl">

            <!-- Brand -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-white/20">
                <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span class="text-white font-bold text-lg tracking-tight">Admin Panel</span>
            </div>

            <!-- Menu -->
            <nav class="flex-1 px-4 py-6 space-y-1.5">

                <p class="px-4 text-[11px] font-semibold text-white/50 uppercase tracking-wider mb-2">Menu</p>

                <a href="{{ url('/admin/dashboard') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('admin/dashboard') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'text-white/85 hover:bg-white/15' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                <a href="{{ url('/admin/inventory') }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('admin/inventory*') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'text-white/85 hover:bg-white/15' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="text-sm">Inventory</span>
                </a>

                <a href="{{ route('admin.riwayat-stok') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.riwayat-stok') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">Riwayat Stok</span>
                </a>

                <a href="{{ route('admin.stock-opname') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.stock-opname*') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <span class="text-sm">Stock Opname</span>
                </a>

                <a href="{{ route('admin.user') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.user*') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-sm">Kelola Data User</span>
                </a>

                <a href="{{ route('admin.supplier') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.supplier*') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="text-sm">Supplier</span>
                </a>

                <a href="{{ route('admin.pembelian') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.pembelian*') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-sm">Pembelian</span>
                </a>

                <a href="{{ route('admin.customer') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors duration-200 {{ request()->routeIs('admin.customer*') ? 'bg-white text-blue-800 shadow-md font-semibold' : 'text-slate-200 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 3a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm">Customer</span>
                </a>

                <a href="{{ url('/admin/setting') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('admin/setting*') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'text-white/85 hover:bg-white/15' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm">Setting</span>
                </a>

            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-white/20">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-white/85 hover:bg-white/15 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-sm">Logout</span>
                    </button>
                </form>
            </div>

        </aside>

        <!-- Main content -->
        <main class="flex-1 p-8 overflow-y-auto">
            {{ $slot }}
        </main>

    </div>

</body>

</html>