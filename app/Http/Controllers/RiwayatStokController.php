<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use Illuminate\Http\Request;

class RiwayatStokController extends Controller
{
    /**
     * Tampilkan halaman Riwayat Stok Admin: log semua pergerakan stok
     * (masuk/keluar/penyesuaian). Bisa difilter berdasarkan jenis
     * pergerakan dan dicari berdasarkan nama sparepart.
     */
    public function index(Request $request)
    {
        $riwayat = StockHistory::with('sparepart')
            ->when($request->jenis, fn ($q, $jenis) => $q->where('jenis', $jenis))
            ->when($request->search, function ($q, $search) {
                $q->whereHas('sparepart', fn ($sub) => $sub->where('nama_sparepart', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.riwayat-stok', compact('riwayat'));
    }
}