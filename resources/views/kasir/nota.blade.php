<x-kasir-layout :title="'Nota Transaksi'">

    <div class="max-w-md mx-auto" x-data="{ showWaModal: false }">

        {{-- Notifikasi sukses --}}
        <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium text-center">
            Transaksi berhasil disimpan.
        </div>

        {{-- Kartu Nota (area yang akan dicetak) --}}
        <div id="nota-print" class="bg-white rounded-2xl shadow-sm p-6">

            <div class="text-center mb-4 pb-4 border-b border-dashed border-slate-300">
                <h2 class="text-lg font-bold text-slate-800">CV. Sanqua</h2>
                <p class="text-xs text-slate-500">Nota Transaksi Sparepart</p>
            </div>

            <div class="text-xs text-slate-500 mb-4 space-y-0.5">
                <div class="flex justify-between">
                    <span>No. Transaksi</span>
                    <span class="font-semibold text-slate-700">{{ $transaction->no_transaksi }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tanggal</span>
                    <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir</span>
                    <span>{{ $transaction->user->name ?? '-' }}</span>
                </div>
            </div>

            <div class="divide-y divide-dashed divide-slate-200 border-y border-dashed border-slate-300 py-2 mb-3">
                @foreach ($transaction->items as $item)
                    <div class="flex items-center justify-between py-2 text-sm">
                        <div>
                            <p class="font-medium text-slate-700">{{ $item->sparepart->nama ?? 'Barang dihapus' }}</p>
                            <p class="text-xs text-slate-400">{{ $item->qty }} × Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-semibold text-slate-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between mb-1">
                <span class="text-sm font-bold text-slate-800">Total</span>
                <span class="text-base font-bold text-blue-700">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                <span>Metode Pembayaran</span>
                <span class="font-semibold">{{ $transaction->metode_pembayaran }}</span>
            </div>

            @if ($transaction->catatan)
                <div class="mt-3 pt-3 border-t border-dashed border-slate-300 text-xs text-slate-500">
                    <span class="font-semibold">Catatan:</span> {{ $transaction->catatan }}
                </div>
            @endif

            <p class="text-center text-xs text-slate-400 mt-4 pt-3 border-t border-dashed border-slate-300">
                Terima kasih telah berbelanja
            </p>
        </div>

        {{-- Tombol aksi --}}
        <div class="flex items-center gap-3 mt-5 print:hidden">
            <button
                type="button"
                onclick="window.print()"
                class="flex-1 flex items-center justify-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-sm font-semibold py-3 rounded-xl shadow-sm transition-all duration-200 active:scale-95 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Nota
            </button>

            <button
                type="button"
                @click="showWaModal = true"
                class="flex-1 flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-3 rounded-xl shadow-md transition-all duration-200 active:scale-95 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12.001 2C6.478 2 2 6.477 2 12c0 1.999.586 3.86 1.594 5.42L2 22l4.708-1.55A9.945 9.945 0 0012.001 22C17.523 22 22 17.523 22 12S17.523 2 12.001 2zm0 18.001a7.955 7.955 0 01-4.052-1.11l-.29-.172-3.021.994.998-2.98-.19-.306A7.955 7.955 0 014.001 12c0-4.411 3.589-8 8-8 4.411 0 8 3.589 8 8s-3.589 8.001-8 8.001z"/>
                </svg>
                Kirim WA
            </button>

            <a href="{{ route('kasir.index') }}"
                class="flex-1 flex items-center justify-center text-center bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold py-3 rounded-xl shadow-md transition-all duration-200 active:scale-95 whitespace-nowrap">
                Transaksi Baru
            </a>
        </div>

        {{-- Modal Kirim via WhatsApp --}}
        <div x-show="showWaModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 print:hidden" style="display: none;">
            <div x-show="showWaModal" @click="showWaModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showWaModal" class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Kirim Nota via WhatsApp</h3>
                    <button @click="showWaModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('kasir.nota.kirim-wa', $transaction) }}" class="space-y-4" target="_blank">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nama Customer</label>
                        <input type="text" name="nama_customer" required
                            class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nomor WhatsApp</label>
                        <input type="text" name="no_wa" required placeholder="08xx..."
                            class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500">
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" @click="showWaModal = false" class="flex-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5">Batal</button>
                        <button type="submit" class="flex-1 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl px-4 py-2.5 shadow-md">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Bersihkan cart lokal begitu transaksi selesai & nota ditampilkan --}}
    <script>
        localStorage.removeItem('kasir_cart');
    </script>

    <style>
        @media print {
            aside, .print\:hidden {
                display: none !important;
            }
            main {
                padding: 0 !important;
            }
            body {
                background: white !important;
            }
        }
    </style>

</x-kasir-layout>