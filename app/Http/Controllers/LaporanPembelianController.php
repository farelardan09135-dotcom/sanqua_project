<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Menangani halaman Laporan Pembelian Owner: filter periode
 * (harian/mingguan/bulanan/tahunan/custom) dan lihat rincian pembelian
 * dari supplier. Meniru pola LaporanPenjualanController.
 */
class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'semua');

        [$start, $end] = $this->resolveDateRange($periode, $request->dari, $request->sampai);

        $base = Purchase::query();
        if ($start && $end) {
            $base->whereBetween('created_at', [$start, $end]);
        }

        $totalPembelian = (clone $base)->count();
        $totalNilai = (clone $base)->sum('total_pembelian');

        $pembelians = (clone $base)
            ->with(['supplier', 'user', 'items.sparepart'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('owner.pembelian', compact('pembelians', 'totalPembelian', 'totalNilai', 'periode'));
    }

    private function resolveDateRange(string $periode, ?string $dari, ?string $sampai): array
    {
        return match ($periode) {
            'harian'   => [now()->startOfDay(), now()->endOfDay()],
            'mingguan' => [now()->startOfWeek(), now()->endOfWeek()],
            'bulanan'  => [now()->startOfMonth(), now()->endOfMonth()],
            'tahunan'  => [now()->startOfYear(), now()->endOfYear()],
            'custom'   => [
                $dari ? Carbon::parse($dari)->startOfDay() : null,
                $sampai ? Carbon::parse($sampai)->endOfDay() : null,
            ],
            default    => [null, null],
        };
    }
}