<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * Generate file Excel untuk Laporan Penjualan, mengikuti rentang tanggal
 * yang sama dengan filter yang sedang aktif di halaman.
 */
class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        protected ?\Illuminate\Support\Carbon $start,
        protected ?\Illuminate\Support\Carbon $end
    ) {}

    public function collection()
    {
        $query = Transaction::with('user')->latest();

        if ($this->start && $this->end) {
            $query->whereBetween('created_at', [$this->start, $this->end]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['No Transaksi', 'Tanggal', 'Kasir', 'Total', 'Metode Pembayaran', 'Catatan'];
    }

    public function map($transaction): array
    {
        return [
            $transaction->no_transaksi,
            $transaction->created_at->format('d/m/Y H:i'),
            $transaction->user->name ?? '-',
            $transaction->total,
            $transaction->metode_pembayaran,
            $transaction->catatan ?? '-',
        ];
    }
}