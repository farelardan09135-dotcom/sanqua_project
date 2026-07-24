<x-admin-layout :title="'Laporan Penjualan'">

    <h1 class="text-2xl font-bold text-blue-700 mb-6">
        Laporan Penjualan
    </h1>

    <div x-data="{
            showDetailModal: false,
            selectedTransaction: null,
            openDetail(transaction) {
                this.selectedTransaction = transaction;
                this.showDetailModal = true;
            }
        }">

        {{-- Filter Periode --}}
        <form method="GET" action="{{ route('admin.penjualan') }}" class="flex flex-wrap items-end gap-3 mb-6">

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Periode</label>
                <select name="periode" onchange="this.form.submit()"
                    class="h-10 pl-3 pr-8 text-sm rounded-lg bg-white border border-slate-200 shadow-sm focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all cursor-pointer">
                    <option value="semua" @selected($periode === 'semua')>Semua</option>
                    <option value="harian" @selected($periode === 'harian')>Harian</option>
                    <option value="mingguan" @selected($periode === 'mingguan')>Mingguan</option>
                    <option value="bulanan" @selected($periode === 'bulanan')>Bulanan</option>
                    <option value="tahunan" @selected($periode === 'tahunan')>Tahunan</option>
                    <option value="custom" @selected($periode === 'custom')>Custom (pilih tanggal)</option>
                </select>
            </div>

            @if ($periode === 'custom')
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Dari</label>
                    <input type="date" name="dari" value="{{ request('dari') }}"
                        class="h-10 px-3 text-sm rounded-lg bg-white border border-slate-200 shadow-sm focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sampai</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="h-10 px-3 text-sm rounded-lg bg-white border border-slate-200 shadow-sm focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all">
                </div>
                <button type="submit"
                    class="h-10 px-4 text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800 rounded-lg transition-colors">
                    Terapkan
                </button>
            @endif

            <a href="{{ route('admin.penjualan.export', request()->query()) }}"
                class="h-10 flex items-center gap-2 px-5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition-all duration-200 active:scale-95 ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                </svg>
                Export Excel
            </a>
        </form>

        {{-- Kartu ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm px-6 py-5">
                <p class="text-xs text-slate-500 mb-1">Total Transaksi</p>
                <p class="text-xl font-bold text-slate-800">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm px-6 py-5">
                <p class="text-xs text-slate-500 mb-1">Total Pendapatan</p>
                <p class="text-xl font-bold text-slate-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Tabel Data Transaksi --}}
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h2 class="text-base font-bold text-slate-800 mb-3">Data Transaksi</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="text-left font-semibold py-2 px-3">No Transaksi</th>
                            <th class="text-left font-semibold py-2 px-3">Tanggal</th>
                            <th class="text-left font-semibold py-2 px-3">Kasir</th>
                            <th class="text-left font-semibold py-2 px-3">Total</th>
                            <th class="text-center font-semibold py-2 px-3">Metode</th>
                            <th class="text-center font-semibold py-2 px-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($transaksis as $trx)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2 px-3 font-medium text-slate-800">{{ $trx->no_transaksi }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-2 px-3 text-slate-500">{{ $trx->user->name ?? '-' }}</td>
                                <td class="py-2 px-3 text-slate-700">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                <td class="py-2 px-3 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                        {{ $trx->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <button type="button" @click="openDetail(@js($trx))"
                                        class="text-blue-600 hover:underline text-xs font-medium">
                                        Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 italic">
                                    Belum ada transaksi pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (pola sama seperti Inventory) --}}
            @if ($transaksis->hasPages())
                <div class="flex items-center justify-center gap-1.5 mt-6 pt-4 border-t border-slate-100">
                    @if ($transaksis->onFirstPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-full text-slate-300 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $transaksis->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all duration-150 active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                        @if ($page == $transaksis->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-bold bg-blue-700 text-white shadow-md shadow-blue-700/30">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-medium text-slate-500 hover:bg-slate-100 transition-all duration-150 active:scale-90">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($transaksis->hasMorePages())
                        <a href="{{ $transaksis->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all duration-150 active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="w-8 h-8 flex items-center justify-center rounded-full text-slate-300 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Modal Detail Transaksi --}}
        <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showDetailModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDetailModal = false" class="absolute inset-0 bg-slate-900/50"></div>

            <div x-show="showDetailModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

                <template x-if="selectedTransaction">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800" x-text="selectedTransaction.no_transaksi"></h3>
                                <p class="text-xs text-slate-400" x-text="selectedTransaction.created_at"></p>
                            </div>
                            <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto mb-3">
                            <template x-for="item in selectedTransaction.items" :key="item.id">
                                <div class="flex items-center justify-between py-2.5">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700" x-text="item.sparepart.nama"></p>
                                        <p class="text-xs text-slate-400" x-text="item.qty + ' × Rp ' + Number(item.harga_satuan).toLocaleString('id-ID')"></p>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700" x-text="'Rp ' + Number(item.subtotal).toLocaleString('id-ID')"></span>
                                </div>
                            </template>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-200">
                            <span class="text-sm font-bold text-slate-800">Total</span>
                            <span class="text-base font-bold text-blue-700" x-text="'Rp ' + Number(selectedTransaction.total).toLocaleString('id-ID')"></span>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                            <span>Metode: <span class="font-semibold" x-text="selectedTransaction.metode_pembayaran"></span></span>
                            <span x-show="selectedTransaction.catatan">Catatan: <span x-text="selectedTransaction.catatan"></span></span>
                        </div>
                    </div>
                </template>

            </div>
        </div>

    </div>

</x-admin-layout>