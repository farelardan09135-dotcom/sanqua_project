@props(['href', 'active' => false])

<a href="{{ $href }}" @click="sidebarOpen = false"
   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all
   {{ $active ? 'bg-white text-blue-600 shadow-md font-semibold' : 'text-white/85 hover:bg-white/15' }}">
    {{ $slot }}
</a>