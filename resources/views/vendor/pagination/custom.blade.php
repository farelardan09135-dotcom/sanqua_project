@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-1.5">

        {{-- Tombol Previous --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-300 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @endif

        {{-- Nomor halaman: window bergeser, maksimal 4 angka sekaligus --}}
        @php
            $windowSize = 4;
            $last = $paginator->lastPage();
            $current = $paginator->currentPage();

            $start = $current;
            $end = $start + $windowSize - 1;

            if ($end > $last) {
                $end = $last;
                $start = max(1, $end - $windowSize + 1);
            }
        @endphp

        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-700 text-white text-sm font-semibold">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $paginator->url($page) }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg text-sm text-slate-600 hover:bg-slate-100 transition-colors">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Tombol Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-300 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif

    </nav>
@endif