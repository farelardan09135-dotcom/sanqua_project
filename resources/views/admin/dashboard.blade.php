<x-admin-layout :title="'Dashboard Admin'">

    <h1 class="text-2xl font-bold text-blue-700 mb-6">
        Dashboard Admin
    </h1>

    <div x-data="{ showModalHabis: false }">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm px-6 py-5">
                <p class="text-xs text-slate-500 mb-1">Total Barang</p>
                <p class="text-xl font-bold text-slate-800">{{ $totalBarang }}</p>
            </div>
            <div class="bg-amber-50 rounded-2xl shadow-sm px-6 py-5">
                <p class="text-xs text-amber-600 mb-1">Stok Menipis</p>
                <p class="text-xl font-bold text-amber-700">{{ $stokMenipis }}</p>
            </div>

            {{-- Stok Habis --}}
            <button type="button" @click="showModalHabis = true"
                class="bg-red-50 rounded-2xl shadow-sm px-6 py-5 text-left hover:shadow-md hover:bg-red-100/70 transition-all duration-200 active:scale-[0.98] cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-red-500 mb-1">Stok Habis</p>
                        <p class="text-xl font-bold text-red-600">{{ $stokHabis }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Barang Perlu Segera Direstock --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-base font-bold text-slate-800 mb-4">Barang Perlu Segera Direstock</h2>

                @forelse ($barangMenipis as $barang)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-100 last:border-0">
                        <span class="text-sm text-slate-700">{{ $barang->nama_sparepart }}</span>
                        <span class="text-xs font-semibold text-amber-600">Sisa {{ $barang->stok }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic">Semua stok barang dalam kondisi aman.</p>
                @endforelse
            </div>

            {{-- Riwayat Stock Opname Terbaru --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-800">Stock Opname Terbaru</h2>
                    <a href="{{ route('admin.stock-opname') }}" class="text-xs font-medium text-blue-600 hover:underline">Lihat semua</a>
                </div>

                @forelse ($opnameTerbaru as $riwayat)
                    <div class="py-2.5 border-b border-slate-100 last:border-0">
                        <div class="flex items-center justify-between mb-0.5">
                            <p class="text-sm font-medium text-slate-700">{{ $riwayat->sparepart->nama ?? 'Barang dihapus' }}</p>
                            <p class="text-xs text-slate-400">{{ $riwayat->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <p class="text-xs text-slate-500">{{ $riwayat->keterangan }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic">Belum ada riwayat stock opname.</p>
                @endforelse
            </div>

        </div>

        {{-- Modal: Daftar Barang Stok Habis --}}
        <div x-show="showModalHabis" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showModalHabis" @click="showModalHabis = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showModalHabis" class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 max-h-[80vh] flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Barang Stok Habis</h3>
                    <button @click="showModalHabis = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 -mx-1 px-1">
                    @forelse ($barangHabis as $barang)
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-100 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $barang->nama_sparepart }}</p>
                                <p class="text-xs text-slate-400">{{ $barang->kategori ?? '-' }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-red-100 text-red-700">Habis</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 italic py-4 text-center">Tidak ada barang yang stoknya habis.</p>
                    @endforelse
                </div>

                <div class="pt-4 mt-2 border-t border-slate-100">
                    <a href="{{ route('admin.inventory') }}" class="text-xs font-medium text-blue-600 hover:underline">
                        Kelola barang di halaman Inventory &rarr;
                    </a>
                </div>
            </div>
        </div>

    </div>

</x-admin-layout>   