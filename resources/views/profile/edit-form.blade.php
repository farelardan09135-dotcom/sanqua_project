<div class="max-w-2xl space-y-6" x-data="{ editing: false }">

    @include('profile.toast')

    {{-- Informasi Profil --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-1">
            <h2 class="text-base font-bold text-slate-800">Informasi Profil</h2>
            <button type="button" @click="editing = !editing"
                class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors duration-200"
                :class="editing ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-blue-50 text-blue-700 hover:bg-blue-100'">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span x-text="editing ? 'Batal' : 'Edit'"></span>
            </button>
        </div>
        <p class="text-xs text-slate-400 mb-5">Ubah nama, username, dan email akun Anda.</p>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    :readonly="!editing"
                    :class="editing ? 'bg-white border-blue-300' : 'bg-slate-100 border-slate-200 text-slate-500 cursor-not-allowed'"
                    class="w-full h-11 px-3 text-sm rounded-xl border focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                    :readonly="!editing"
                    :class="editing ? 'bg-white border-blue-300' : 'bg-slate-100 border-slate-200 text-slate-500 cursor-not-allowed'"
                    class="w-full h-11 px-3 text-sm rounded-xl border focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors">
                @error('username')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    :readonly="!editing"
                    :class="editing ? 'bg-white border-blue-300' : 'bg-slate-100 border-slate-200 text-slate-500 cursor-not-allowed'"
                    class="w-full h-11 px-3 text-sm rounded-xl border focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-colors">
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" x-show="editing" x-cloak
                class="text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-5 py-2.5 shadow-md transition-all duration-200 active:scale-95">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Link ke halaman Ubah Password --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-slate-800">Password</h2>
            <p class="text-xs text-slate-400">Ubah password akun Anda secara berkala.</p>
        </div>
        <a href="{{ route('profile.password') }}"
            class="flex items-center gap-2 text-sm font-semibold text-white bg-slate-700 hover:bg-slate-800 rounded-xl px-5 py-2.5 shadow-md transition-all duration-200 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z" />
            </svg>
            Ubah Password
        </a>
    </div>

</div>