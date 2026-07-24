<x-guest-layout>

    <div class="w-full max-w-90">

        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-900/20 overflow-hidden">

            <div class="p-8">

                <!-- Logo -->
                <div class="flex justify-center mb-5">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 object-contain">
                </div>

                <!-- Title -->
                <div class="text-center mb-7">
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                        Reset Password
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">
                        Buat password baru untuk akun Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input
                                id="email"
                                class="w-full h-11 pl-10 pr-4 text-sm rounded-xl bg-slate-50 border border-slate-200 placeholder:text-slate-400 focus:bg-white transition-all"
                                type="email"
                                name="email"
                                placeholder="Email"
                                value="{{ old('email', $request->email) }}"
                                required
                                autofocus
                                autocomplete="username"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
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
                                placeholder="Password Baru"
                                required
                                autocomplete="new-password"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z" />
                                </svg>
                            </span>
                            <input
                                id="password_confirmation"
                                class="w-full h-11 pl-10 pr-4 text-sm rounded-xl bg-slate-50 border border-slate-200 placeholder:text-slate-400 focus:bg-white transition-all"
                                type="password"
                                name="password_confirmation"
                                placeholder="Konfirmasi Password Baru"
                                required
                                autocomplete="new-password"
                            >
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-slate-800 hover:bg-slate-900 text-white rounded-xl py-3 text-sm font-semibold shadow-lg shadow-slate-800/20 transition-all active:scale-[0.98]">
                        Reset Password
                    </button>

                </form>

            </div>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            © {{ date('Y') }} — Sistem Aman & Terpercaya
        </p>

    </div>

</x-guest-layout>