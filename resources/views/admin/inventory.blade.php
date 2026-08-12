<x-admin-layout :title="'Inventory'">

    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-blue-700 mb-6">Inventory</h1>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded-xl bg-red-50 text-red-600 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div x-data="{
        showAddModal: false,
        showEditModal: false,
        showFilterPanel: false,
        showDeleteModal: false,
        editMode: false,
        editingItem: { id: null, nama_sparepart: '', kategori: '', stok: 0, harga: 0 },
        deleteTarget: { id: null, nama_sparepart: '' },
        openEdit(item, id) {
            this.editingItem = { id: id, nama: item.nama_sparepart, kategori: item.kategori, stok: item.stok, harga: item.harga };
            this.showEditModal = true;
        },
        openDelete(id, nama_sparepart) {
            this.deleteTarget = { id: id, nama: nama_sparepart };
            this.showDeleteModal = true;
        }
    }">

        <!-- Baris: SearchBar + Filter (popover) + tombol Edit & Tambah -->
        <div class="flex items-center justify-between gap-4 mb-4">

            <div class="flex items-center gap-2 w-full max-w-xl">

                <!-- Search Bar -->
                <form method="GET" action="{{ route('admin.inventory') }}" class="flex items-center gap-2 flex-1 h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40 focus-within:border-blue-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari sparepart..."
                        class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400"
                    >
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if(request('search'))
                        <a href="{{ route('admin.inventory', request()->except('search')) }}" class="text-slate-400 hover:text-slate-600 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </form>

                <!-- Tombol Filter (ikon corong, popover) -->
                <div class="relative shrink-0">
                    <button
                        type="button"
                        @click="showFilterPanel = !showFilterPanel"
                        :class="(showFilterPanel || {{ request('kategori') || request('sort') ? 'true' : 'false' }})
                            ? 'bg-blue-700 text-white border-blue-700'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        class="relative h-10 w-10 flex items-center justify-center rounded-xl border shadow-sm transition-all duration-200 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M6 8h12M9 12h6M11 16h2" />
                        </svg>
                        @if(request('kategori') || request('sort'))
                            <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-amber-400 border-2 border-white"></span>
                        @endif
                    </button>

                    <!-- Popover panel -->
                    <div
                        x-show="showFilterPanel"
                        x-cloak
                        @click.outside="showFilterPanel = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2"
                        class="absolute left-0 top-14 z-20 w-72 bg-white rounded-xl shadow-xl border border-slate-100 p-4"
                        style="display: none;">

                        <form method="GET" action="{{ route('admin.inventory') }}" class="space-y-3">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kategori</label>
                                <select name="kategori"
                                    class="w-full h-10 px-3 text-sm rounded-lg bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all cursor-pointer">
                                    <option value="">Semua</option>
                                    @foreach ($kategoriList as $kategori)
                                        <option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>
                                            {{ $kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Urutkan</label>
                                <select name="sort"
                                    class="w-full h-10 px-3 text-sm rounded-lg bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all cursor-pointer">
                                    <option value="">Nama (A-Z)</option>
                                    <option value="harga_tertinggi" @selected(request('sort') === 'harga_tertinggi')>Harga Termahal</option>
                                    <option value="harga_terendah" @selected(request('sort') === 'harga_terendah')>Harga Termurah</option>
                                    <option value="stok_terbanyak" @selected(request('sort') === 'stok_terbanyak')>Stok Terbanyak</option>
                                    <option value="stok_tersedikit" @selected(request('sort') === 'stok_tersedikit')>Stok Tersedikit</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                @if(request('kategori') || request('sort'))
                                    <a href="{{ route('admin.inventory', request()->only('search')) }}"
                                        class="flex-1 text-center text-xs font-medium text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-lg px-3 py-2 transition-colors">
                                        Reset
                                    </a>
                                @endif
                                <button type="submit"
                                    class="flex-1 text-center text-xs font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg px-3 py-2 transition-colors">
                                    Terapkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Tombol Edit & Tambah -->
            <div class="flex items-center gap-3 shrink-0">
                <button
                    type="button"
                    @click="editMode = !editMode"
                    :class="editMode
                        ? 'bg-blue-700 text-white border-blue-700'
                        : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                    class="flex items-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm border transition-all duration-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span x-text="editMode ? 'Selesai' : 'Edit'"></span>
                </button>

                <button
                    type="button"
                    @click="showAddModal = true"
                    class="flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all duration-200 active:scale-95 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>

        </div>

        <!-- Daftar Sparepart -->
        <div class="bg-white rounded-2xl shadow-sm p-5">

            <h2 class="text-lg font-bold text-slate-800 mb-3">
                Daftar Sparepart
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="text-left font-semibold py-2 px-3">No</th>
                            <th class="text-left font-semibold py-2 px-3">Nama Sparepart</th>
                            <th class="text-left font-semibold py-2 px-3">Kategori</th>
                            <th class="text-center font-semibold py-2 px-3">Stok</th>
                            <th class="text-left font-semibold py-2 px-3">Harga</th>
                            <th class="text-center font-semibold py-2 px-3">Status</th>
                            <th class="text-center font-semibold py-2 px-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($spareparts as $index => $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2 px-3 text-slate-500">{{ $spareparts->firstItem() + $index }}</td>
                                <td class="py-2 px-3 font-medium text-slate-800">{{ $item->nama_sparepart }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $item->kategori }}</td>
                                <td class="py-2 px-3 text-center text-slate-700">{{ $item->stok }}</td>
                                <td class="py-2 px-3 text-slate-700">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="py-2 px-3 text-center">
                                    @if ($item->stok == 0)
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">Habis</span>
                                    @elseif ($item->stok < 5)
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">Menipis</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">Tersedia</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            @click="openEdit(@js($item), {{ $item->id }})"
                                            :class="editMode ? 'opacity-100 scale-100' : 'opacity-40 scale-90 pointer-events-none'"
                                            class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button" @click="openDelete({{ $item->id }}, @js($item->nama_sparepart))"
                                            :class="editMode ? 'opacity-100 scale-100' : 'opacity-40 scale-90 pointer-events-none'"
                                            class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 italic">
                                    Belum ada data sparepart yang cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($spareparts->hasPages())
                <div class="mt-4 pt-4 border-t border-slate-100">
                    {{ $spareparts->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Tambah Sparepart -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showAddModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showAddModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Tambah Sparepart</h3>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.inventory.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nama Sparepart</label>
                        <input type="text" name="nama_sparepart" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all" placeholder="Contoh: Kampas Rem Depan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Kategori</label>
                        <input type="text" name="kategori" required list="kategori-list" class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all" placeholder="Contoh: Rem, Mesin, Kelistrikan">
                        <datalist id="kategori-list">
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Stok</label>
                            <input type="number" name="stok" min="0" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Harga</label>
                            <input type="number" name="harga" min="0" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all" placeholder="0">
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="showAddModal = false" class="flex-1 text-center text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5 transition-all duration-200 active:scale-95">Batal</button>
                        <button type="submit" class="flex-1 text-center text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-4 py-2.5 shadow-md transition-all duration-200 active:scale-95 hover:shadow-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Sparepart (tidak berubah dari sebelumnya, tambahkan datalist kategori juga) -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showEditModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showEditModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Edit Sparepart</h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" :action="`/admin/inventory/${editingItem.id}`" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nama Sparepart</label>
                        <input type="text" name="nama_sparepart" x-model="editingItem.nama_sparepart" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Kategori</label>
                        <input type="text" name="kategori" x-model="editingItem.kategori" required list="kategori-list-edit" class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                        <datalist id="kategori-list-edit">
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Stok</label>
                            <input type="number" name="stok" x-model="editingItem.stok" min="0" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Harga</label>
                            <input type="number" name="harga" x-model="editingItem.harga" min="0" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="showEditModal = false" class="flex-1 text-center text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5 transition-all duration-200 active:scale-95">Batal</button>
                        <button type="submit" class="flex-1 text-center text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-4 py-2.5 shadow-md transition-all duration-200 active:scale-95 hover:shadow-lg">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Konfirmasi Hapus --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showDeleteModal" @click="showDeleteModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showDeleteModal" class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-red-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Sparepart?</h3>
                <p class="text-sm text-slate-500 mb-6">
                    Yakin ingin menghapus <span class="font-semibold text-slate-700" x-text="deleteTarget.nama_sparepart"></span>? Tindakan ini tidak bisa dibatalkan.
                </p>
                <form :action="`/admin/inventory/${deleteTarget.id}`" method="POST" class="flex items-center gap-3">
                    @csrf @method('DELETE')
                    <button type="button" @click="showDeleteModal = false" class="flex-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5">Batal</button>
                    <button type="submit" class="flex-1 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl px-4 py-2.5 shadow-md">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>