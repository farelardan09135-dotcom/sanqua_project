<x-owner-layout :title="'Dashboard Owner'">

    <h1 class="text-2xl font-bold text-indigo-700 mb-6">
        Dashboard Owner
    </h1>

    <div
        class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch"
        x-data="{
            mode: 'harian',
            dataHarian: @js($chartHarian),
            dataBulanan: @js($chartBulanan),
            selectedIndex: null,
            totalTransaksiKeseluruhan: {{ $totalTransaksi }},
            totalPendapatanKeseluruhan: {{ $totalPendapatan }},
            catatanList: @js($catatanList),
            catatanIndex: 0,

            get currentData() {
                return this.mode === 'harian' ? this.dataHarian : this.dataBulanan;
            },
            get selected() {
                return this.selectedIndex !== null ? this.currentData[this.selectedIndex] : null;
            },
            get displayTransaksi() {
                return this.selected ? this.selected.jumlah : this.totalTransaksiKeseluruhan;
            },
            get displayPendapatan() {
                return this.selected ? this.selected.total : this.totalPendapatanKeseluruhan;
            },
            formatRupiah(n) {
                return 'Rp ' + Number(n).toLocaleString('id-ID');
            },
            resetSelection() {
                this.selectedIndex = null;
            },
            initChart(canvas) {
                const ctx = canvas.getContext('2d');
                const self = this;
                canvas._chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: this.dataHarian.map(d => d.label),
                        datasets: [{ label: 'Pendapatan', data: this.dataHarian.map(d => d.total), backgroundColor: '#4f46e5', borderRadius: 6, maxBarThickness: 36 }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: (evt, elements) => {
                            if (elements.length > 0) self.selectedIndex = elements[0].index;
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') } }
                        },
                        scales: { y: { ticks: { callback: (value) => 'Rp ' + (value / 1000) + 'rb' } } }
                    }
                });
            },
            updateChart() {
                const chartInstance = this.$refs.chartCanvas._chartInstance;
                chartInstance.data.labels = this.currentData.map(d => d.label);
                chartInstance.data.datasets[0].data = this.currentData.map(d => d.total);
                chartInstance.update();
            },
            showDeleteCatatanModal: false,
            deleteCatatanTarget: null,

            openDeleteCatatan() {
                this.deleteCatatanTarget = this.catatanList[this.catatanIndex];
                this.showDeleteCatatanModal = true;
            },
            confirmHapusCatatan() {
                const id = this.deleteCatatanTarget.id;
                fetch(`/owner/catatan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    }
                }).then(res => {
                    if (res.ok) {
                        this.catatanList = this.catatanList.filter(c => c.id !== id);
                        if (this.catatanIndex >= this.catatanList.length) {
                            this.catatanIndex = Math.max(0, this.catatanList.length - 1);
                        }
                    }
                    this.showDeleteCatatanModal = false;
                });
            }
        }"
        x-init="initChart($refs.chartCanvas)"
        >

        {{-- Chart Laporan Penjualan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-800">Laporan Penjualan</h2>
                <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1">
                   <button type="button" @click="mode = 'harian'; selectedIndex = null; updateChart()"
                        :class="mode === 'harian' ? 'bg-white shadow-sm text-indigo-700' : 'text-slate-500 hover:text-slate-700'"
                        class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all duration-150">Harian</button>
                   <button type="button" @click="mode = 'bulanan'; selectedIndex = null; updateChart()"
                        :class="mode === 'bulanan' ? 'bg-white shadow-sm text-indigo-700' : 'text-slate-500 hover:text-slate-700'"
                        class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all duration-150">Bulanan</button>                </div>
            </div>
            <p class="text-xs text-slate-400 mb-2">Klik salah satu bar untuk lihat detail hari/bulan itu.</p>
            <div class="flex-1 min-h-75">
                <canvas x-ref="chartCanvas"></canvas>
            </div>
        </div>

        {{-- Kartu ringkasan --}}
        <div class="flex flex-col gap-4">

            {{-- Total Transaksi --}}
            <div class="bg-white rounded-2xl shadow-sm px-6 py-6 flex-1 flex flex-col justify-center relative">
                <template x-if="selected">
                    <button @click="resetSelection()" class="absolute top-3 right-3 text-slate-400 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </template>
                <p class="text-xs text-slate-500 mb-1 text-center" x-text="selected ? `Transaksi (${selected.label})` : 'Total Transaksi'"></p>
                <p class="text-xl font-bold text-slate-800 text-center" x-text="displayTransaksi"></p>
            </div>

            {{-- Total Pendapatan --}}
            <div class="bg-white rounded-2xl shadow-sm px-6 py-6 flex-1 flex flex-col justify-center relative">
                <template x-if="selected">
                    <button @click="resetSelection()" class="absolute top-3 right-3 text-slate-400 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </template>
                <p class="text-xs text-slate-500 mb-1 text-center" x-text="selected ? `Pendapatan (${selected.label})` : 'Total Pendapatan'"></p>
                <p class="text-xl font-bold text-slate-800 text-center" x-text="formatRupiah(displayPendapatan)"></p>
            </div>

            {{-- Catatan (carousel) --}}
            <div class="bg-linear-to-br from-indigo-50 to-white rounded-2xl shadow-sm px-5 py-5 border border-indigo-100/50">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Catatan Kasir</p>
                    </div>
                    <button x-show="catatanList.length > 0" @click="openDeleteCatatan()"
                        class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <template x-if="catatanList.length === 0">
                    <div class="text-center py-4">
                        <p class="text-xs text-slate-400 italic">Belum ada catatan dari kasir</p>
                    </div>
                </template>

                <template x-if="catatanList.length > 0">
                    <div>
                        <div class="relative bg-white rounded-xl px-4 py-3 shadow-sm">
                            <svg class="absolute -top-1.5 -left-1 w-6 h-6 text-indigo-200" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179zm10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621.537-.278 1.24-.375 1.929-.311 1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 01-3.5 3.5c-1.073 0-2.099-.49-2.748-1.179z"/>
                            </svg>
                            <p class="text-sm text-slate-600 leading-relaxed pl-3" x-text="catatanList[catatanIndex].catatan"></p>
                        </div>

                        <div class="flex items-center justify-between mt-2.5 px-1">
                            <p class="text-[11px] font-medium text-indigo-400" x-text="catatanList[catatanIndex].no_transaksi"></p>
                            <p class="text-[11px] text-slate-400" x-text="catatanList[catatanIndex].tanggal"></p>
                        </div>

                        <div class="flex items-center justify-center gap-1.5 mt-3" x-show="catatanList.length > 1">
                            <template x-for="(c, i) in catatanList" :key="c.id">
                                <button @click="catatanIndex = i"
                                    class="h-1.5 rounded-full transition-all duration-200"
                                    :class="i === catatanIndex ? 'bg-indigo-600 w-5' : 'bg-indigo-200 w-1.5 hover:bg-indigo-300'">
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Modal Konfirmasi Hapus Catatan --}}
        <div x-show="showDeleteCatatanModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="showDeleteCatatanModal" @click="showDeleteCatatanModal = false" class="absolute inset-0 bg-slate-900/50"></div>
            <div x-show="showDeleteCatatanModal" class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-red-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Hapus Catatan?</h3>
                <p class="text-sm text-slate-500 mb-6">
                    Catatan dari transaksi <span class="font-semibold text-slate-700" x-text="deleteCatatanTarget?.no_transaksi"></span> akan dihapus. Tindakan ini tidak bisa dibatalkan.
                </p>
                <div class="flex items-center gap-3">
                    <button type="button" @click="showDeleteCatatanModal = false"
                        class="flex-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl px-4 py-2.5">
                        Batal
                    </button>
                    <button type="button" @click="confirmHapusCatatan()"
                        class="flex-1 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl px-4 py-2.5 shadow-md">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>

    </div>

</x-owner-layout>