<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class OwnerDashboardController extends Controller
{
    /**
     * Tampilkan dashboard utama Owner: ringkasan total transaksi & pendapatan,
     * beberapa catatan terbaru dari kasir (bisa digeser & dihapus), serta
     * data chart harian/bulanan (masing-masing bawa jumlah transaksi juga,
     * bukan cuma total pendapatan, supaya bisa diklik per-bar).
     */
    public function index()
    {
        $totalTransaksi = Transaction::count();
        $totalPendapatan = Transaction::sum('total');

        // Beberapa catatan terbaru (bukan cuma 1), lengkap id transaksi untuk hapus
        $catatanList = Transaction::whereNotNull('catatan')
            ->where('catatan', '!=', '')
            ->latest()
            ->take(10)
            ->get(['id', 'no_transaksi', 'catatan', 'created_at'])
            ->map(fn ($t) => [
                'id' => $t->id,
                'no_transaksi' => $t->no_transaksi,
                'catatan' => $t->catatan,
                'tanggal' => $t->created_at->format('d/m/Y H:i'),
            ]);

        // Data harian 14 hari terakhir: total pendapatan + jumlah transaksi
        $harianRaw = Transaction::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d') as periode, SUM(total) as total, COUNT(*) as jumlah")
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('periode')
            ->get()
            ->keyBy('periode');

        $chartHarian = collect(range(13, 0))->map(function ($i) use ($harianRaw) {
            $date = now()->subDays($i);
            $row = $harianRaw[$date->format('Y-m-d')] ?? null;
            return [
                'label' => $date->format('d/m'),
                'total' => (int) ($row->total ?? 0),
                'jumlah' => (int) ($row->jumlah ?? 0),
            ];
        })->values();

        // Data bulanan 12 bulan terakhir: total pendapatan + jumlah transaksi
        $bulananRaw = Transaction::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as periode, SUM(total) as total, COUNT(*) as jumlah")
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('periode')
            ->get()
            ->keyBy('periode');

        $chartBulanan = collect(range(11, 0))->map(function ($i) use ($bulananRaw) {
            $date = now()->subMonths($i);
            $row = $bulananRaw[$date->format('Y-m')] ?? null;
            return [
                'label' => $date->translatedFormat('M Y'),
                'total' => (int) ($row->total ?? 0),
                'jumlah' => (int) ($row->jumlah ?? 0),
            ];
        })->values();

        return view('owner.dashboard', [
            'totalTransaksi' => $totalTransaksi,
            'totalPendapatan' => $totalPendapatan,
            'catatanList' => $catatanList,
            'chartHarian' => $chartHarian,
            'chartBulanan' => $chartBulanan,
        ]);
    }

    /**
     * Hapus catatan dari 1 transaksi tertentu (bukan hapus transaksinya).
     * Dipanggil dari tombol hapus di carousel Catatan.
     */
    public function destroyCatatan(Transaction $transaction)
    {
        $transaction->update(['catatan' => null]);

        return response()->json(['status' => 'ok']);
    }
}