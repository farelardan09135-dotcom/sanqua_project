<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('PATCH')

    <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
            class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
        @error('name')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Username</label>
        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
            class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
        @error('username')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-600 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
            class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
        @error('email')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
        class="text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-5 py-2.5 shadow-md transition-all duration-200 active:scale-95">
        Simpan Perubahan
    </button>
</form>

    {{-- Ganti Password --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-base font-bold text-slate-800 mb-1">Ganti Password</h2>
        <p class="text-xs text-slate-400 mb-5">Pastikan gunakan password yang kuat dan mudah Anda ingat.</p>

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
                class="text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-5 py-2.5 shadow-md transition-all duration-200 active:scale-95">
                Update Password
            </button>
        </form>
    </div>
</x-app-layout>
