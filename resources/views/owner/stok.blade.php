<x-owner-layout :title="'Laporan Stok'">

    <h1 class="text-2xl font-bold text-indigo-700 mb-6">Laporan Stok</h1>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Jenis Barang</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($totalJenisBarang) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Unit Stok</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($totalStok) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Nilai Inventaris</p>
            <p class="text-2xl font-bold text-indigo-700">Rp {{ number_format($nilaiInventaris, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5 {{ ($jumlahMenipis + $jumlahHabis) > 0 ? 'ring-2 ring-amber-400' : '' }}">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Menipis / Habis</p>
            <p class="text-2xl font-bold {{ ($jumlahMenipis + $jumlahHabis) > 0 ? 'text-amber-600' : 'text-slate-800' }}">
                {{ $jumlahMenipis }} / {{ $jumlahHabis }}
            </p>
        </div>
    </div>

    @if (($jumlahMenipis + $jumlahHabis) > 0)
        <div class="mb-5 px-4 py-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-sm font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            Ada {{ $jumlahMenipis }} sparepart menipis (stok < 10) dan {{ $jumlahHabis }} sparepart habis, perlu segera dipesan ulang.
        </div>
    @endif

    {{-- Search --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <form method="GET" action="{{ route('owner.stok') }}" class="flex items-center gap-2 w-full max-w-md h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500/40 focus-within:border-indigo-500 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sparepart..."
                class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
            @if (request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
        </form>

        <form method="GET" action="{{ route('owner.stok') }}" class="shrink-0">
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select name="sort" onchange="this.form.submit()"
                class="h-10 pl-4 pr-8 text-sm rounded-xl bg-white border border-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                <option value="stok_tersedikit" {{ $sort === 'stok_tersedikit' ? 'selected' : '' }}>Stok: Tersedikit</option>
                <option value="stok_terbanyak" {{ $sort === 'stok_terbanyak' ? 'selected' : '' }}>Stok: Terbanyak</option>
                <option value="harga_termurah" {{ $sort === 'harga_termurah' ? 'selected' : '' }}>Harga: Termurah</option>
                <option value="harga_termahal" {{ $sort === 'harga_termahal' ? 'selected' : '' }}>Harga: Termahal</option>
            </select>
        </form>
    </div>

    {{-- Tabel Stok --}}
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <h2 class="text-base font-bold text-slate-800 mb-3">Daftar Stok Sparepart</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="text-left font-semibold py-2 px-3">Nama Sparepart</th>
                        <th class="text-left font-semibold py-2 px-3">Kategori</th>
                        <th class="text-right font-semibold py-2 px-3">Harga</th>
                        <th class="text-right font-semibold py-2 px-3">Stok</th>
                        <th class="text-right font-semibold py-2 px-3">Nilai Stok</th>
                        <th class="text-center font-semibold py-2 px-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($spareparts as $item)
                        <tr class="hover:bg-slate-50 transition-colors {{ $item->status !== 'Tersedia' ? 'bg-amber-50/50' : '' }}">
                            <td class="py-2 px-3 font-medium text-slate-800">{{ $item->nama }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->kategori ?? '-' }}</td>
                            <td class="py-2 px-3 text-right text-slate-500">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-right font-semibold {{ $item->status !== 'Tersedia' ? 'text-amber-600' : 'text-slate-700' }}">{{ $item->stok }}</td>
                            <td class="py-2 px-3 text-right text-slate-500">Rp {{ number_format($item->harga * $item->stok, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-center">
                                <span @class([
                                    'inline-flex px-2.5 py-1 rounded-full text-xs font-semibold',
                                    'bg-red-100 text-red-700' => $item->status === 'Habis',
                                    'bg-amber-100 text-amber-700' => $item->status === 'Menipis',
                                    'bg-emerald-50 text-emerald-700' => $item->status === 'Tersedia',
                                ])>
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-slate-400 italic">Belum ada data sparepart.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($spareparts->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $spareparts->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</x-owner-layout>