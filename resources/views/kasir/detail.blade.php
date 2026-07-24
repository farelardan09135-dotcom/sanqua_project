<x-kasir-layout :title="'Detail Transaksi'">

    <h1 class="text-xl font-bold text-blue-700 mb-4">Detail Transaksi</h1>

    <div x-data="{
            items: @js($items),
            metode: '',
            get total() {
                return this.items.reduce((sum, i) => sum + (i.harga_satuan * i.qty), 0);
            },
            increment(item) {
                if (item.qty < item.stok) item.qty++;
            },
            decrement(item) {
                item.qty--;
                if (item.qty <= 0) {
                    this.items = this.items.filter(i => i.id !== item.id);
                }
            },
            removeItem(id) {
                this.items = this.items.filter(i => i.id !== id);
            },
            formatRupiah(n) {
                return 'Rp ' + n.toLocaleString('id-ID');
            },
            submitBayar() {
                document.getElementById('cart-json').value = JSON.stringify(
                    this.items.map(i => ({ id: i.id, qty: i.qty }))
                );
            }
        }">

        <form method="POST" action="{{ route('kasir.bayar') }}" @submit="submitBayar()">
            @csrf
            <input type="hidden" name="cart" id="cart-json">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start mb-5">

                {{-- Rincian Barang (sekarang bisa diedit) --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5">
                    <h2 class="text-base font-bold text-slate-800 mb-3">Rincian Barang</h2>

                    <template x-if="items.length === 0">
                        <p class="text-center text-slate-400 italic text-sm py-8">
                            Semua barang sudah dihapus. Klik "Batal" untuk kembali ke Kasir.
                        </p>
                    </template>

                    <div class="divide-y divide-slate-100">
                        <template x-for="item in items" :key="item.id">
                            <div class="flex items-center justify-between py-3">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-800" x-text="item.nama"></p>
                                    <p class="text-xs text-slate-400" x-text="formatRupiah(item.harga_satuan) + ' / item'"></p>
                                </div>

                                <div class="flex items-center gap-2 mx-4">
                                    <button type="button" @click="decrement(item)"
                                        class="w-7 h-7 flex items-center justify-center rounded-md bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">-</button>
                                    <span class="text-sm font-semibold w-6 text-center" x-text="item.qty"></span>
                                    <button type="button" @click="increment(item)"
                                        class="w-7 h-7 flex items-center justify-center rounded-md bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">+</button>
                                </div>

                                <span class="text-sm font-semibold text-slate-700 w-24 text-right" x-text="formatRupiah(item.harga_satuan * item.qty)"></span>

                                <button type="button" @click="removeItem(item.id)" class="ml-3 text-slate-400 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="flex items-center justify-between pt-4 mt-2 border-t border-slate-200">
                        <span class="text-sm font-bold text-slate-800">Total</span>
                        <span class="text-lg font-bold text-blue-700" x-text="formatRupiah(total)"></span>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <h2 class="text-base font-bold text-slate-800 mb-3">Metode Pembayaran</h2>

                    <div class="space-y-2">
                        @foreach (['Cash', 'Transfer', 'QRIS'] as $option)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer transition-all"
                                   :class="metode === '{{ $option }}' ? 'border-blue-500 bg-blue-50' : 'hover:bg-slate-50'">
                                <input type="radio" name="metode_pembayaran" value="{{ $option }}" x-model="metode" required class="accent-blue-700">
                                <span class="text-sm font-medium text-slate-700">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Catatan (opsional)</label>
                        <textarea name="catatan" rows="3"
                            class="w-full px-3 py-2 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all"
                            placeholder="Contoh: kurang bayar Rp5.000"></textarea>
                    </div>
                </div>

            </div>

            {{-- Tombol Bayar --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('kasir.index') }}"
                    class="text-center text-sm font-medium text-slate-500 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl px-6 py-3 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    :disabled="!metode || items.length === 0"
                    class="flex-1 bg-blue-700 hover:bg-blue-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-base font-bold py-3 rounded-xl shadow-md transition-all duration-200 active:scale-95">
                    Bayar
                </button>
            </div>
        </form>

    </div>

</x-kasir-layout>