<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $totalTransaksi = Transaction::count();
        $totalPendapatan = Transaction::sum('total');

        $catatan = Transaction::whereNotNull('catatan')
            ->where('catatan', '!=', '')
            ->latest()
            ->value('catatan');

        $harianRaw = Transaction::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as periode, SUM(total) as total")
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('periode')
            ->pluck('total', 'periode');

        $chartHarian = collect(range(13, 0))->map(function ($i) use ($harianRaw) {
            $date = now()->subDays($i);
            return ['label' => $date->format('d/m'), 'total' => (int) ($harianRaw[$date->format('Y-m-d')] ?? 0)];
        })->values();

        $bulananRaw = Transaction::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periode, SUM(total) as total")
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('periode')
            ->pluck('total', 'periode');

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