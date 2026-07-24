<x-admin-layout :title="'Kelola Data User'">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-blue-700">Kelola Data User</h1>
    </div>

    <div x-data="{
            showAddModal: false,
            showConfirmModal: false,
            confirmTarget: { id: null, name: '', action: '' }
        }">

        @if (session('status'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 text-red-600 text-sm font-medium">{{ session('error') }}</div>
        @endif

        <div class="flex items-center justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('admin.user') }}" class="flex items-center gap-2 w-full max-w-md h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama user..."
                    class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
            </form>

            <button type="button" @click="showAddModal = true"
                class="flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all duration-200 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="text-left font-semibold py-2 px-3">Nama</th>
                            <th class="text-left font-semibold py-2 px-3">Email</th>
                            <th class="text-center font-semibold py-2 px-3">Role</th>
                            <th class="text-center font-semibold py-2 px-3">Status</th>
                            <th class="text-center font-semibold py-2 px-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $item)
                            @php
                                $register = $item->registers()->latest()->first() ?? null;
                                $status = $register->status ?? 'aktif';
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2 px-3 font-medium text-slate-800">{{ $item->name }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $item->email }}</td>
                                <td class="py-2 px-3 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 capitalize">{{ $item->role }}</span>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    @if ($item->id !== auth()->id())
                                        <button type="button"
                                            @click="confirmTarget = { id: {{ $item->id }}, name: @js($item->name), action: '{{ $status === 'aktif' ? 'menonaktifkan' : 'mengaktifkan' }}' }; showConfirmModal = true"
                                            class="text-xs font-medium text-blue-600 hover:underline">
                                            {{ $status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-300">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada data user.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="mt-6 pt-4 border-t border-slate-100">{{ $users->links() }}</div>
            @endif
        </div>

        {{-- Modal Tambah User --}}
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showAddModal" @click="showAddModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showAddModal" class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Tambah User Baru</h3>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.user.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nama</label>
                        <input type="text" name="name" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Username</label>
                        <input type="text" name="username" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Password</label>
                        <input type="password" name="password" required minlength="6" class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Role</label>
                        <select name="role" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40">
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="showAddModal = false" class="flex-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5">Batal</button>
                        <button type="submit" class="flex-1 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-4 py-2.5 shadow-md">Daftarkan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Konfirmasi Ubah Status --}}
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showConfirmModal" @click="showConfirmModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showConfirmModal" class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-amber-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Ubah Status Akun</h3>
                <p class="text-sm text-slate-500 mb-6">
                    Yakin ingin <span x-text="confirmTarget.action"></span> akun <span class="font-semibold text-slate-700" x-text="confirmTarget.name"></span>?
                </p>
                <form :action="`/admin/user/${confirmTarget.id}/toggle-status`" method="POST" class="flex items-center gap-3">
                    @csrf @method('PATCH')
                    <button type="button" @click="showConfirmModal = false" class="flex-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5">Batal</button>
                    <button type="submit" class="flex-1 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-4 py-2.5 shadow-md">Ya, Lanjutkan</button>
                </form>
            </div>
        </div>

    </div>

</x-admin-layout>