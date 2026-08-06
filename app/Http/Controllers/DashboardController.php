<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\StockHistory;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard utama Admin: ringkasan total barang,
     * jumlah barang stok menipis & habis, daftar barang yang
     * perlu diwaspadai, dan riwayat stock opname terbaru.
     */
    public function index()
    {
        $totalBarang = Sparepart::count();
        $stokMenipis = Sparepart::where('stok', '>', 0)->where('stok', '<', 5)->count();
        $stokHabis = Sparepart::where('stok', 0)->count();

        $barangMenipis = Sparepart::where('stok', '>', 0)->where('stok', '<', 5)
            ->orderBy('stok')
            ->limit(5)
            ->get();

        // Daftar lengkap barang yang stoknya benar-benar habis (untuk modal)
        $barangHabis = Sparepart::where('stok', 0)
            ->orderBy('nama_sparepart')
            ->get();

        // 5 riwayat Stock Opname (penyesuaian) terbaru, untuk ditampilkan di dashboard
        $opnameTerbaru = StockHistory::where('jenis', 'penyesuaian')
            ->with('sparepart')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBarang',
            'stokMenipis',
            'stokHabis',
            'barangMenipis',
            'barangHabis',
            'opnameTerbaru'
        ));
    }
}