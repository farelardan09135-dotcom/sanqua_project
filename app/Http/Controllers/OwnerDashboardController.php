<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class OwnerDashboardController extends Controller
{
    /**
     * Tampilkan dashboard utama Owner: ringkasan total transaksi
     * & pendapatan sepanjang waktu, catatan transaksi terbaru,
     * serta data chart pendapatan 14 hari terakhir dan 12 bulan terakhir.
     */
    public function index()
    {
        $totalTransaksi = Transaction::count();
        $totalPendapatan = Transaction::sum('total');

        // Ambil catatan (note) dari transaksi terbaru yang punya catatan (tidak kosong)
        $catatan = Transaction::whereNotNull('catatan')
            ->where('catatan', '!=', '')
            ->latest()
            ->value('catatan');

        // Total pendapatan per hari, 14 hari terakhir (untuk chart harian)
        $harianRaw = Transaction::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as periode, SUM(total) as total")
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('periode')
            ->pluck('total', 'periode');

        // Susun ulang jadi array lengkap 14 hari (hari tanpa transaksi = 0)
        $chartHarian = collect(range(13, 0))->map(function ($i) use ($harianRaw) {
            $date = now()->subDays($i);
            return ['label' => $date->format('d/m'), 'total' => (int) ($harianRaw[$date->format('Y-m-d')] ?? 0)];
        })->values();

        // Total pendapatan per bulan, 12 bulan terakhir (untuk chart bulanan)
        $bulananRaw = Transaction::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periode, SUM(total) as total")
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('periode')
            ->pluck('total', 'periode');

        // Susun ulang jadi array lengkap 12 bulan (bulan tanpa transaksi = 0)
        $chartBulanan = collect(range(11, 0))->map(function ($i) use ($bulananRaw) {
            $date = now()->subMonths($i);
            return ['label' => $date->format('M Y'), 'total' => (int) ($bulananRaw[$date->format('Y-m')] ?? 0)];
        })->values();

        return view('owner.dashboard', [
            'totalTransaksi' => number_format($totalTransaksi, 0, ',', '.'),
            'totalPendapatan' => number_format($totalPendapatan, 0, ',', '.'),
            'catatan' => $catatan,
            'chartHarian' => $chartHarian,
            'chartBulanan' => $chartBulanan,
        ]);
    }
}