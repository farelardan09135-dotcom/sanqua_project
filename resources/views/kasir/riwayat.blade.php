<x-kasir-layout :title="'Riwayat Transaksi'">

    <h1 class="text-xl font-bold text-blue-700 mb-4">Riwayat Transaksi Saya</h1>

    {{-- Search & Filter --}}
    <div class="mb-5">
        <form method="GET" action="{{ route('kasir.riwayat') }}" class="flex flex-wrap items-center gap-3">

            {{-- Search --}}
            <div class="flex items-center gap-2 h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40 focus-within:border-blue-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. transaksi..."
                    class="w-56 h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
            </div>

            {{-- Filter tanggal spesifik --}}
            <div class="flex items-center gap-2 h-10 px-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                <label class="text-xs text-slate-400 shrink-0">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="text-sm bg-transparent border-none outline-none focus:ring-0">
            </div>


            <button type="submit"
                class="h-10 px-4 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                Filter
            </button>

            @if (request('search') || request('tanggal'))
                <a href="{{ route('kasir.riwayat') }}"
                    class="h-10 px-4 flex items-center rounded-xl bg-red-400 text-sm text-gray-800 hover:bg-slate-50 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500">
                        <th class="text-left font-semibold py-2 px-3">No Transaksi</th>
                        <th class="text-left font-semibold py-2 px-3">Tanggal</th>
                        <th class="text-left font-semibold py-2 px-3">Jumlah Item</th>
                        <th class="text-left font-semibold py-2 px-3">Metode Bayar</th>
                        <th class="text-right font-semibold py-2 px-3">Total</th>
                        <th class="text-center font-semibold py-2 px-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($transaksis as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-2 px-3 font-medium text-slate-800">{{ $item->no_transaksi }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->items->count() }} item</td>
                            <td class="py-2 px-3 text-slate-500">{{ $item->metode_pembayaran }}</td>
                            <td class="py-2 px-3 text-right font-semibold text-slate-700">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-center">
                              <a href="{{ route('kasir.nota', $item->no_transaksi) }}"class="text-blue-600 hover:underline text-xs font-medium">Lihat Nota</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-slate-400 italic">Anda belum pernah melakukan transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transaksis->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>

</x-kasir-layout>