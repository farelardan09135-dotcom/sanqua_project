@props(['brand' => 'Panel', 'gradient' => 'from-blue-600 to-sky-500', 'title' => null])

<div x-data="{ sidebarOpen: false }" class="min-h-screen w-full bg-linear-to-b from-sky-200 via-sky-100 to-blue-50 flex">

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:static inset-y-0 left-0 z-50 w-64 shrink-0 bg-linear-to-b {{ $gradient }} flex flex-col shadow-xl transition-transform duration-300 ease-in-out">

        {{-- Brand --}}
        <div class="h-20 flex items-center justify-between gap-3 px-6 border-b border-white/20">
            <div class="flex items-center gap-3 min-w-0">
                {{ $brandIcon ?? '' }}
                <span class="text-white font-bold text-lg tracking-tight truncate">{{ $brand }}</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-white/80 hover:text-white shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[11px] font-semibold text-white/50 uppercase tracking-wider mb-2">Menu</p>
            {{ $nav }}
        </nav>

        {{-- Logout --}}
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

    {{-- Konten --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Topbar, hanya muncul di mobile --}}
        <header class="lg:hidden sticky top-0 z-30 h-16 flex items-center gap-3 px-4 bg-white/90 backdrop-blur border-b border-slate-200">
            <button @click="sidebarOpen = true" class="text-slate-600 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <span class="font-bold text-slate-800">{{ $title ?? $brand }}</span>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>

</div>