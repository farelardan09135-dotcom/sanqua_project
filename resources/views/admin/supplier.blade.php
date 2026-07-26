<x-admin-layout :title="'Supplier'">

    <h1 class="text-2xl font-bold text-blue-700 mb-6">Supplier</h1>

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
            showDeleteModal: false,
            editMode: false,
            editingItem: { id: null, nama_supplier: '', kontak: '', alamat: '' },
            deleteTarget: { id: null, nama: '' },
            openEdit(item, id) {
                this.editingItem = { id: id, nama_supplier: item.nama_supplier, kontak: item.kontak, alamat: item.alamat };
                this.showEditModal = true;
            },
            openDelete(id, nama) {
                this.deleteTarget = { id: id, nama: nama };
                this.showDeleteModal = true;
            }
        }">

        <div class="flex items-center justify-between gap-4 mb-6">
            <form method="GET" action="{{ route('admin.supplier') }}" class="flex items-center gap-2 w-full max-w-md h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40 focus-within:border-blue-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari supplier..."
                    class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
            </form>

            <div class="flex items-center gap-3 shrink-0">
                <button type="button" @click="editMode = !editMode"
                    :class="editMode ? 'bg-blue-700 text-white border-blue-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                    class="flex items-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm border transition-all duration-200 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span x-text="editMode ? 'Selesai' : 'Edit'"></span>
                </button>

                <button type="button" @click="showAddModal = true"
                    class="flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all duration-200 active:scale-95 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h2 class="text-base font-bold text-slate-800 mb-3">Daftar Supplier</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="text-left font-semibold py-2 px-3">No</th>
                            <th class="text-left font-semibold py-2 px-3">Nama Supplier</th>
                            <th class="text-left font-semibold py-2 px-3">Kontak</th>
                            <th class="text-left font-semibold py-2 px-3">Alamat</th>
                            <th class="text-center font-semibold py-2 px-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($suppliers as $index => $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2 px-3 text-slate-500">{{ $suppliers->firstItem() + $index }}</td>
                                <td class="py-2 px-3 font-medium text-slate-800">{{ $item->nama_supplier }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $item->kontak }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $item->alamat }}</td>
                                <td class="py-2 px-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="openEdit(@js($item), {{ $item->id }})"
                                            :class="editMode ? 'opacity-100 scale-100' : 'opacity-40 scale-90 pointer-events-none'"
                                            class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button" @click="openDelete({{ $item->id }}, @js($item->nama_supplier))"
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
                            <tr><td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada data supplier.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suppliers->hasPages())
                <div class="flex items-center justify-center gap-1.5 mt-6 pt-4 border-t border-slate-100">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Tambah --}}
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showAddModal" @click="showAddModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showAddModal" class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Tambah Supplier</h3>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.supplier.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nama Supplier</label>
                        <input type="text" name="nama_supplier" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Kontak</label>
                        <input type="text" name="kontak" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500" placeholder="08xx...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Alamat</label>
                        <input type="text" name="alamat" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="showAddModal = false" class="flex-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5">Batal</button>
                        <button type="submit" class="flex-1 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-4 py-2.5 shadow-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showEditModal" @click="showEditModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showEditModal" class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Edit Supplier</h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" :action="`/admin/supplier/${editingItem.id}`" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nama Supplier</label>
                        <input type="text" name="nama_supplier" x-model="editingItem.nama_supplier" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Kontak</label>
                        <input type="text" name="kontak" x-model="editingItem.kontak" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Alamat</label>
                        <input type="text" name="alamat" x-model="editingItem.alamat" required class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="showEditModal = false" class="flex-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5">Batal</button>
                        <button type="submit" class="flex-1 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-xl px-4 py-2.5 shadow-md">Simpan Perubahan</button>
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
                <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Supplier?</h3>
                <p class="text-sm text-slate-500 mb-6">
                    Yakin ingin menghapus <span class="font-semibold text-slate-700" x-text="deleteTarget.nama"></span>? Tindakan ini tidak bisa dibatalkan.
                </p>
                <form :action="`/admin/supplier/${deleteTarget.id}`" method="POST" class="flex items-center gap-3">
                    @csrf @method('DELETE')
                    <button type="button" @click="showDeleteModal = false" class="flex-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5">Batal</button>
                    <button type="submit" class="flex-1 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl px-4 py-2.5 shadow-md">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>