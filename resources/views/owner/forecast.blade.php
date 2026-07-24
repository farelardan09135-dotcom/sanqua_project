<x-owner-layout :title="'Forecast Penjualan'">

    <h1 class="text-2xl font-bold text-indigo-700 mb-2">Forecast & Tren Penjualan</h1>
    <p class="text-sm text-slate-500 mb-6">
        Prediksi dihitung menggunakan metode <span class="font-medium">Simple Moving Average</span> berdasarkan rata-rata penjualan 3 bulan terakhir.
    </p>

    @if ($barangTerlaris->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-slate-400 italic">
            Belum ada cukup data transaksi untuk menghitung forecast.
        </div>
    @else

        {{-- Grafik Barang Terlaris --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6"
             x-data
             x-init="
                const ctx = $refs.chartTerlaris.getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @js($barangTerlaris->pluck('nama')),
                        datasets: [{
                            label: 'Total Terjual (3 Bulan Terakhir)',
                            data: @js($barangTerlaris->pluck('total_terjual')),
                            backgroundColor: '#4f46e5',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });
             ">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Top 10 Barang Terlaris</h2>
            <div class="h-96">
                <canvas x-ref="chartTerlaris"></canvas>
            </div>
        </div>

        {{-- Tabel Detail + Prediksi SMA --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Detail Penjualan & Prediksi Bulan Depan</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500">
                            <th class="text-left font-semibold py-2 px-3">Barang</th>
                            @foreach ($labelBulan as $label)
                                <th class="text-center font-semibold py-2 px-3">{{ $label }}</th>
                            @endforeach
                            <th class="text-center font-semibold py-2 px-3 bg-indigo-50 text-indigo-700 rounded-t-lg">
                                Prediksi Bulan Depan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($barangTerlaris as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2 px-3 font-medium text-slate-800">{{ $item['nama'] }}</td>
                                @foreach ($item['riwayat'] as $bulan)
                                    <td class="py-2 px-3 text-center text-slate-600">{{ $bulan['qty'] }}</td>
                                @endforeach
                                <td class="py-2 px-3 text-center font-bold text-indigo-700 bg-indigo-50/50">
                                    ≈ {{ $item['prediksi'] }} unit
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="text-xs text-slate-400 mt-4">
                Rumus: SMA = (Qty Bulan-1 + Qty Bulan-2 + Qty Bulan-3) / 3
            </p>
        </div>

    @endif

</x-owner-layout>