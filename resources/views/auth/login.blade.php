<x-guest-layout>

    <div class="w-full max-w-90">

        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-900/20 overflow-hidden">

            <div class="p-8">

                <!-- Logo -->
                <div class="flex justify-center mb-5">
                    <img src="{{ asset('images/logo_sanqua.jpeg') }}" alt="Logo" class="w-16 h-16 object-contain">
                </div>

                <!-- Title -->
                <div class="text-center mb-7">
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                        Selamat datang
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">
                        Masuk untuk melanjutkan
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Username -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input
                                id="username"
                                class="w-full h-11 pl-10 pr-4 text-sm rounded-xl bg-slate-50 border border-slate-200 placeholder:text-slate-400 focus:bg-white transition-all"
                                type="text"
                                name="username"
                                placeholder="Masukkan username"
                                value="{{ old('username') }}"
                                autofocus
                                autocomplete="username"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z" />
                                </svg>
                            </span>
                            <input
                                id="password"
                                class="w-full h-11 pl-10 pr-4 text-sm rounded-xl bg-slate-50 border border-slate-200 placeholder:text-slate-400 focus:bg-white transition-all"
                                type="password"
                                name="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <!-- Ingat saya / Lupa Password -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer select-none">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-slate-300 text-slate-700 focus:ring-slate-400">
                            <span class="text-xs font-medium text-slate-500">Ingat saya</span>
                        </label>

                        <a href="{{ route('password.request') }}"
                           class="text-xs font-medium text-slate-500 hover:text-slate-700 transition-colors">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="w-full bg-slate-800 hover:bg-slate-900 text-white rounded-xl py-3 text-sm font-semibold shadow-lg shadow-slate-800/20 transition-all active:scale-[0.98]">
                        Login
                    </button>

                </form>

            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            © {{ date('Y') }} — Sistem Aman & Terpercaya
        </p>

    </div>

</x-guest-layout>