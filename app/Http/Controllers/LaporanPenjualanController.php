<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualanExport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'semua');

        [$start, $end] = $this->resolveDateRange($periode, $request->dari, $request->sampai);

        $base = Transaction::query();
        if ($start && $end) {
            $base->whereBetween('created_at', [$start, $end]);
        }

        $totalTransaksi = (clone $base)->count();
        $totalPendapatan = (clone $base)->sum('total');

        $transaksis = (clone $base)
            ->with(['user', 'items.sparepart'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('owner.penjualan', compact('transaksis', 'totalTransaksi', 'totalPendapatan', 'periode'));
    }

    public function export(Request $request)
    {
        $periode = $request->input('periode', 'semua');
        [$start, $end] = $this->resolveDateRange($periode, $request->dari, $request->sampai);

        $filename = 'laporan-penjualan-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(new LaporanPenjualanExport($start, $end), $filename);
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