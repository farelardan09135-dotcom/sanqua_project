<x-admin-layout :title="'Tambah Pembelian'">

    <h1 class="text-xl font-bold text-blue-700 mb-4">Tambah Pembelian</h1>

    <div x-data="{
            cart: [],
            supplierId: '',
            search: '',
            addToCart(item) {
                const existing = this.cart.find(i => i.id === item.id);
                if (!existing) {
                    this.cart.push({ id: item.id, nama: item.nama, qty: 1, harga_beli: 0 });
                }
            },
            removeItem(id) {
                this.cart = this.cart.filter(i => i.id !== id);
            },
            get total() {
                return this.cart.reduce((sum, i) => sum + (i.qty * i.harga_beli), 0);
            },
            formatRupiah(n) {
                return 'Rp ' + Number(n || 0).toLocaleString('id-ID');
            },
            submitForm() {
                if (!this.supplierId || this.cart.length === 0) return;
                document.getElementById('items-json').value = JSON.stringify(
                    this.cart.map(i => ({ id: i.id, qty: i.qty, harga_beli: i.harga_beli }))
                );
                document.getElementById('pembelian-form').submit();
            }
        }">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

            {{-- Pilih Supplier + Daftar Barang --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-600 mb-1">Supplier</label>
                    <select x-model="supplierId" class="w-full h-11 px-3 text-sm rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-bold text-slate-800">Pilih Barang</h2>
                </div>

                {{-- Search Bar --}}
                <div class="mb-3">
                    <div class="flex items-center gap-2 w-full h-10 px-4 rounded-xl bg-slate-50 border border-slate-200 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/40 focus-within:border-blue-500 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" x-model="search" placeholder="Cari sparepart..."
                            class="w-full h-full text-sm bg-transparent border-none outline-none focus:ring-0 placeholder:text-slate-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-100 overflow-y-auto pr-1">
                    @foreach ($spareparts as $item)
                        <button type="button"
                            x-show="search === '' || @js($item->nama).toLowerCase().includes(search.toLowerCase())"
                            @click="addToCart(@js($item))"
                            class="text-left p-3 rounded-xl border border-slate-200 hover:border-blue-400 hover:shadow-md transition-all duration-150">
                            <p class="text-sm font-semibold text-slate-800">{{ $item->nama }}</p>
                            <p class="text-xs text-slate-400">Stok saat ini: {{ $item->stok }}</p>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Keranjang Pembelian --}}
            <div class="bg-white rounded-2xl shadow-sm p-5 flex flex-col">
                <h2 class="text-base font-bold text-slate-800 mb-3">Barang Dibeli</h2>

                <div class="flex-1 space-y-3 max-h-95 overflow-y-auto pr-1">
                    <template x-if="cart.length === 0">
                        <p class="text-center text-slate-400 italic text-sm py-8">Belum ada barang dipilih.</p>
                    </template>

                    <template x-for="item in cart" :key="item.id">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-slate-800" x-text="item.nama"></p>
                                <button type="button" @click="removeItem(item.id)" class="text-slate-400 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-slate-500 mb-1">Qty</label>
                                    <input type="number" x-model.number="item.qty" min="1" class="w-full h-9 px-2 text-sm rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-500 mb-1">Harga Beli</label>
                                    <input type="number" x-model.number="item.harga_beli" min="0" class="w-full h-9 px-2 text-sm rounded-lg border border-slate-200">
                                </div>
                            </div>
                            <p class="text-xs text-right mt-1 font-semibold text-blue-700" x-text="formatRupiah(item.qty * item.harga_beli)"></p>
                        </div>
                    </template>
                </div>

                <div class="pt-4 mt-3 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-slate-600">Total</span>
                        <span class="text-lg font-bold text-blue-700" x-text="formatRupiah(total)"></span>
                    </div>
                    <button type="button" @click="submitForm()"
                        :disabled="!supplierId || cart.length === 0"
                        class="w-full bg-blue-700 hover:bg-blue-800 disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-sm font-semibold py-3 rounded-xl shadow-md transition-all duration-200 active:scale-95">
                        Simpan Pembelian
                    </button>
                </div>
            </div>

        </div>

        <form id="pembelian-form" method="POST" action="{{ route('admin.pembelian.store') }}" class="hidden">
            @csrf
            <input type="hidden" name="supplier_id" :value="supplierId">
            <input type="hidden" name="items" id="items-json">
        </form>

    </div>

</x-admin-layout>