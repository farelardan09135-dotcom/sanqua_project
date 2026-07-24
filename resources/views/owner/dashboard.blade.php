<x-owner-layout :title="'Dashboard Owner'">

    <h1 class="text-2xl font-bold text-indigo-700 mb-6">
        Dashboard Owner
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

        <div
            class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6 flex flex-col"
            x-data="{ mode: 'harian' }"
            x-init="
                const ctx = $refs.chartCanvas.getContext('2d');
                const dataHarian = @js($chartHarian);
                const dataBulanan = @js($chartBulanan);
                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: dataHarian.map(d => d.label),
                        datasets: [{ label: 'Pendapatan', data: dataHarian.map(d => d.total), backgroundColor: '#4f46e5', borderRadius: 6, maxBarThickness: 36 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') } } },
                        scales: { y: { ticks: { callback: (value) => 'Rp ' + (value / 1000) + 'rb' } } }
                    }
                });
                $watch('mode', (value) => {
                    const source = value === 'harian' ? dataHarian : dataBulanan;
                    chart.data.labels = source.map(d => d.label);
                    chart.data.datasets[0].data = source.map(d => d.total);
                    chart.update();
                });
            ">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-800">Laporan Penjualan</h2>
                <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1">
                    <button type="button" @click="mode = 'harian'" :class="mode === 'harian' ? 'bg-white shadow-sm text-indigo-700' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all duration-150">Harian</button>
                    <button type="button" @click="mode = 'bulanan'" :class="mode === 'bulanan' ? 'bg-white shadow-sm text-indigo-700' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all duration-150">Bulanan</button>
                </div>
            </div>
            <div class="flex-1 min-h-75">
                <canvas x-ref="chartCanvas"></canvas>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <div class="bg-white rounded-2xl shadow-sm px-6 py-6 flex-1 flex flex-col justify-center">
                <p class="text-xs text-slate-500 mb-1 text-center">Total Transaksi</p>
                <p class="text-xl font-bold text-slate-800 text-center">{{ $totalTransaksi }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm px-6 py-6 flex-1 flex flex-col justify-center">
                <p class="text-xs text-slate-500 mb-1 text-center">Total Pendapatan</p>
                <p class="text-xl font-bold text-slate-800 text-center">Rp {{ $totalPendapatan }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm px-6 py-4">
                <p class="text-sm font-semibold text-slate-700 text-center">Catatan</p>
                <p class="text-xs text-slate-400 text-center mt-1 italic">{{ $catatan ?? 'Belum ada catatan dari kasir' }}</p>
            </div>
        </div>
    </div>

</x-owner-layout>