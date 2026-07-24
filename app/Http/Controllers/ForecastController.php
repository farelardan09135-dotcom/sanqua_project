<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class ForecastController extends Controller
{
    /**
     * Menampilkan halaman Forecast: tren penjualan 3 bulan terakhir
     * per barang + prediksi bulan depan pakai Simple Moving Average (SMA),
     * serta ranking barang terlaris.
     */
    public function index()
    {
        $jumlahBulan = 3;

        // Ambil semua barang yang PERNAH terjual dalam rentang waktu yang dianalisis
        $spareparts = Sparepart::whereHas('transactionItems', function ($q) use ($jumlahBulan) {
            $q->whereHas('transaction', fn ($t) => $t->where('created_at', '>=', now()->subMonths($jumlahBulan)->startOfMonth()));
        })->get();

        $forecastData = $spareparts->map(function ($sparepart) use ($jumlahBulan) {
            // Ambil qty terjual per bulan, untuk barang ini, N bulan terakhir
            $perBulan = TransactionItem::where('sparepart_id', $sparepart->id)
                ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->selectRaw("DATE_FORMAT(transactions.created_at, '%Y-%m') as bulan, SUM(transaction_items.qty) as total_qty")
                ->where('transactions.created_at', '>=', now()->subMonths($jumlahBulan)->startOfMonth())
                ->groupBy('bulan')
                ->pluck('total_qty', 'bulan');

            // Susun array lengkap N bulan terakhir (bulan tanpa penjualan = 0)
            $riwayat = collect(range($jumlahBulan - 1, 0))->map(function ($i) use ($perBulan) {
                $bulanKey = now()->subMonths($i)->format('Y-m');
                return [
                    'label' => now()->subMonths($i)->translatedFormat('M Y'),
                    'qty' => (int) ($perBulan[$bulanKey] ?? 0),
                ];
            });

            // Simple Moving Average = rata-rata qty selama N bulan
            $sma = round($riwayat->avg('qty'), 1);

            return [
                'id' => $sparepart->id,
                'nama' => $sparepart->nama,
                'kategori' => $sparepart->kategori,
                'riwayat' => $riwayat,
                'total_terjual' => $riwayat->sum('qty'),
                'prediksi' => $sma,
            ];
        });

        // Ranking barang terlaris (berdasarkan total qty terjual, bukan prediksi)
        $barangTerlaris = $forecastData->sortByDesc('total_terjual')->take(10)->values();

        return view('owner.forecast', [
            'barangTerlaris' => $barangTerlaris,
            'labelBulan' => collect(range($jumlahBulan - 1, 0))->map(fn ($i) => now()->subMonths($i)->translatedFormat('M Y')),
        ]);
    }
}