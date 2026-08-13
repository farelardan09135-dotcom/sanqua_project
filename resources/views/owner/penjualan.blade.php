<x-owner-layout :title="'Laporan Penjualan'">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-indigo-700">Laporan Penjualan</h1>
        <a href="{{ route('owner.penjualan.export', request()->query()) }}"
            class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all duration-200 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Excel
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Transaksi</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($totalTransaksi) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Pendapatan</p>
            <p class="text-2xl font-bold text-indigo-700">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filter Periode --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-5" x-data="{ periode: '{{ $periode }}' }">
        <form method="GET" action="{{ route('owner.penjualan') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Periode</label>
                <select name="periode" x-model="periode" onchange="this.form.submit()"
                    class="h-10 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                    <option value="semua" {{ $periode === 'semua' ? 'selected' : '' }}>Semua</option>
                    <option value="harian" {{ $periode === 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="mingguan" {{ $periode === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                    <option value="bulanan" {{ $periode === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ $periode === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    <option value="custom" {{ $periode === 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>

            <template x-if="periode === 'custom'">
                <div class="flex items-end gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Dari</label>
                        <input type="date" name="dari" value="{{ request('dari') }}"
                            class="h-10 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Sampai</label>
                        <input type="date" name="sampai" value="{{ request('sampai') }}"
                            class="h-10 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                    </div>
                    <button type="submit"
                        class="h-10 px-5 text-sm font-semibold text-white bg-indigo-700 hover:bg-indigo-800 rounded-xl shadow-md">
                        Terapkan
                    </button>
                </div>
            </template>
        </form>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <h2 class="text-base font-bold text-slate-800 mb-3">Rincian Transaksi</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="text-left font-semibold py-2 px-3">No Transaksi</th>
                        <th class="text-left font-semibold py-2 px-3">Tanggal</th>
                        <th class="text-left font-semibold py-2 px-3">Kasir</th>
                        <th class="text-left font-semibold py-2 px-3">Metode Bayar</th>
                        <th class="text-right font-semibold py-2 px-3">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($transaksis as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-2 px-3 font-medium text-slate-800">{{ $item->no_transaksi }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->user->name ?? '-' }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->metode_pembayaran }}</td>
                            <td class="py-2 px-3 text-right font-semibold text-slate-700">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada transaksi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transaksis->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $transaksis->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</x-owner-layout>