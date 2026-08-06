<x-kasir-layout :title="'Cek Barang'">

    <h1 class="text-xl font-bold text-blue-700 mb-4">Cek Barang</h1>

    {{-- Search --}}
    <div class="mb-5">
        <form method="GET" action="{{ route('kasir.cek-barang') }}" class="flex items-center gap-2 w-full max-w-md h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40 focus-within:border-blue-500 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama sparepart..."
                class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
        </form>
    </div>

    {{-- Grid Barang --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($spareparts as $item)
            <div class="bg-white rounded-2xl shadow-sm p-4 border border-slate-100">
                <div class="flex items-start justify-between mb-2">
                    <p class="text-sm font-semibold text-slate-800">{{ $item->nama_sparepart }}</p>
                    <span @class([
                        'inline-flex shrink-0 px-2 py-0.5 rounded-full text-[11px] font-semibold ml-2',
                        'bg-red-100 text-red-700' => $item->status === 'Habis',
                        'bg-amber-100 text-amber-700' => $item->status === 'Menipis',
                        'bg-emerald-50 text-emerald-700' => $item->status === 'Tersedia',
                    ])>
                        {{ $item->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mb-3">{{ $item->kategori ?? '-' }}</p>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <span class="text-sm font-bold text-blue-700">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                    <span class="text-xs text-slate-500">Stok: <span class="font-semibold text-slate-700">{{ $item->stok }}</span></span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 italic bg-white rounded-2xl shadow-sm">
                Sparepart tidak ditemukan.
            </div>
        @endforelse
    </div>

    @if ($spareparts->hasPages())
        <div class="mt-6 pt-4">
            {{ $spareparts->links() }}
        </div>
    @endif

</x-kasir-layout>