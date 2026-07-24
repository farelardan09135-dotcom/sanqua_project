@php
    $layout = match (true) {
        auth()->user()->isOwner() => 'owner-layout',
        auth()->user()->isAdmin() => 'admin-layout',
        default => 'kasir-layout',
    };
@endphp

<x-dynamic-component :component="$layout" :title="'Ubah Password'">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ url()->previous() }}" class="text-slate-400 hover:text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Ubah Password</h1>
    </div>

    <div class="max-w-md bg-white rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password"
                    class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                @error('current_password', 'updatePassword')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Password Baru</label>
                <input type="password" name="password"
                    class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                @error('password', 'updatePassword')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                    class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
            </div>

            <button type="submit"
                class="w-full text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-5 py-2.5 shadow-md transition-all duration-200 active:scale-95">
                Update Password
            </button>
        </form>
    </div>

    @include('profile.toast')

</x-dynamic-component>