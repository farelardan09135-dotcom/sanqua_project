@if (session('status'))
    @php
        $message = match (session('status')) {
            'profile-updated' => 'Data berhasil diubah.',
            'password-updated' => 'Password berhasil diubah.',
            default => session('status'),
        };
    @endphp

    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-5 right-5 z-50 flex items-center gap-2.5 px-4 py-3 rounded-xl bg-emerald-600 text-white text-sm font-medium shadow-xl"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ $message }}
    </div>
@endif