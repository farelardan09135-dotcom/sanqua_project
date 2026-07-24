<x-owner-layout :title="'Laporan Pembelian'">

    <h1 class="text-2xl font-bold text-indigo-700 mb-6">Laporan Pembelian</h1>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Pembelian</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($totalPembelian) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1">Total Nilai Pembelian</p>
            <p class="text-2xl font-bold text-indigo-700">Rp {{ number_format($totalNilai, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filter Periode --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 mb-5" x-data="{ periode: '{{ $periode }}' }">
        <form method="GET" action="{{ route('owner.pembelian') }}" class="flex flex-wrap items-end gap-3">
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

    {{-- Tabel Pembelian --}}
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <h2 class="text-base font-bold text-slate-800 mb-3">Rincian Pembelian</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="text-left font-semibold py-2 px-3">No Pembelian</th>
                        <th class="text-left font-semibold py-2 px-3">Tanggal</th>
                        <th class="text-left font-semibold py-2 px-3">Supplier</th>
                        <th class="text-left font-semibold py-2 px-3">Diinput Oleh</th>
                        <th class="text-right font-semibold py-2 px-3">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pembelians as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-2 px-3 font-medium text-slate-800">{{ $item->no_pembelian }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->supplier->nama_supplier ?? '-' }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->user->name ?? '-' }}</td>
                            <td class="py-2 px-3 text-right font-semibold text-slate-700">Rp {{ number_format($item->total_pembelian, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-slate-400 italic">Belum ada pembelian pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pembelians->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $pembelians->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</x-owner-layout>