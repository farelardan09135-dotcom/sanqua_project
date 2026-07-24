<x-kasir-layout :title="'Kasir'">
    
    <div x-data="{
        cart: JSON.parse(localStorage.getItem('kasir_cart') || '[]'),
        search: '',
        saveCart() {
            localStorage.setItem('kasir_cart', JSON.stringify(this.cart));
        },
        addToCart(item) {
            const existing = this.cart.find(i => i.id === item.id);
            if (existing) {
                if (existing.qty < item.stok) existing.qty++;
            } else {
                this.cart.push({ id: item.id, nama: item.nama, harga: item.harga, stok: item.stok, qty: 1 });
            }
            this.saveCart();
        },
        increment(item) {
            if (item.qty < item.stok) item.qty++;
            this.saveCart();
        },
        decrement(item) {
            item.qty--;
            if (item.qty <= 0) {
                this.cart = this.cart.filter(i => i.id !== item.id);
            }
            this.saveCart();
        },
        removeItem(id) {
            this.cart = this.cart.filter(i => i.id !== id);
            this.saveCart();
        },
        get total() {
            return this.cart.reduce((sum, i) => sum + (i.harga * i.qty), 0);
        },
        formatRupiah(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        },
        submitCheckout() {
            if (this.cart.length === 0) return;
            document.getElementById('cart-json').value = JSON.stringify(
                this.cart.map(i => ({ id: i.id, qty: i.qty }))
            );
            document.getElementById('checkout-form').submit();
        }
    }">

        <div class="mb-4">
            <h1 class="text-xl font-bold text-blue-700">Transaksi Baru</h1>
            <p class="text-sm text-slate-400">Selamat bertugas, {{ auth()->user()->name }}.</p>
        </div>

        {{-- Notifikasi status/error dari session --}}
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

        {{-- Search Bar --}}
            <div class="mb-4">
                <div class="flex items-center gap-2 w-full max-w-md h-10 px-4 rounded-xl bg-white border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40 focus-within:border-blue-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="search" placeholder="Cari sparepart..."
                        class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
                </div>
            </div>
        </form>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

            {{-- Daftar Sparepart --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5">
                <h2 class="text-base font-bold text-slate-800 mb-3">Daftar Sparepart</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-130 overflow-y-auto pr-1">
                    @forelse ($spareparts as $item)
                        <button
                            type="button"
                            x-show="search === '' || @js($item->nama).toLowerCase().includes(search.toLowerCase())"
                            @click="addToCart(@js($item))"
                            {{ $item->stok == 0 ? 'disabled' : '' }}
                            class="text-left p-3 rounded-xl border border-slate-200 hover:border-blue-400 hover:shadow-md transition-all duration-150 disabled:opacity-40 disabled:cursor-not-allowed">
                            <p class="text-sm font-semibold text-slate-800">{{ $item->nama }}</p>
                            <p class="text-xs text-slate-400 mb-1">{{ $item->kategori }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-blue-700">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-400">Stok: {{ $item->stok }}</span>
                            </div>
                        </button>
                    @empty
                        <p class="col-span-2 text-center text-slate-400 italic py-8">Sparepart tidak ditemukan.</p>
                    @endforelse
                </div>
            </div>

            {{-- List Sparepart (Keranjang) --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col h-full">
                <h2 class="text-base font-bold text-slate-800 mb-3">List Sparepart</h2>

                <div class="flex-1 space-y-2 max-h-95 overflow-y-auto pr-1">
                    <template x-if="cart.length === 0">
                        <p class="text-center text-slate-400 italic text-sm py-8">Belum ada barang dipilih.</p>
                    </template>

                    <template x-for="item in cart" :key="item.id">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="text-sm font-medium text-slate-800" x-text="item.nama"></p>
                                <button type="button" @click="removeItem(item.id)" class="text-slate-400 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="decrement(item)" class="w-6 h-6 flex items-center justify-center rounded-md bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">-</button>
                                    <span class="text-sm font-semibold w-5 text-center" x-text="item.qty"></span>
                                    <button type="button" @click="increment(item)" class="w-6 h-6 flex items-center justify-center rounded-md bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-colors">+</button>
                                </div>
                                <span class="text-sm font-semibold text-blue-700" x-text="formatRupiah(item.harga * item.qty)"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="pt-4 mt-3 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-slate-600">Total</span>
                        <span class="text-lg font-bold text-blue-700" x-text="formatRupiah(total)"></span>
                    </div>
                    <button
                        type="button"
                        @click="submitCheckout()"
                        :disabled="cart.length === 0"
                        class="w-full bg-blue-700 hover:bg-blue-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-sm font-semibold py-3 rounded-xl shadow-md transition-all duration-200 active:scale-95">
                        Lanjutkan ke Pembayaran
                    </button>
                </div>
            </div>

        </div>

        {{-- Form tersembunyi untuk kirim keranjang ke server sebagai JSON --}}
        <form id="checkout-form" method="POST" action="{{ route('kasir.checkout') }}" class="hidden">
            @csrf
            <input type="hidden" name="cart" id="cart-json">
        </form>

    </div>

</x-kasir-layout>