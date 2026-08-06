<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Menangani proses Stock Opname: pencocokan stok fisik gudang
 * dengan stok yang tercatat di sistem. Kalau ada selisih, stok
 * di-update dan dicatat ke Riwayat Stok (jenis: penyesuaian).
 */
class StockOpnameController extends Controller
{
    /**
     * Tampilkan form Stock Opname berisi seluruh daftar sparepart,
     * untuk diisi jumlah stok fisik hasil pengecekan gudang.
     */
    public function index()
    {
        $spareparts = Sparepart::orderBy('nama')->get();

        return view('admin.stock-opname', compact('spareparts'));
    }

    /**
     * Proses hasil input stock opname: bandingkan stok fisik vs stok sistem
     * untuk tiap barang, update stok kalau ada selisih, dan catat riwayatnya.
     * Barang yang stoknya sudah cocok (selisih 0) dilewati, tidak dicatat.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:spareparts,id',
            'items.*.stok_fisik' => 'required|integer|min:0',
        ]);

        $hasilOpname = [];

        DB::transaction(function () use ($validated, &$hasilOpname) {
            foreach ($validated['items'] as $row) {
                $sparepart = Sparepart::lockForUpdate()->find($row['id']);
                $stokFisik = (int) $row['stok_fisik'];
                $selisih = $stokFisik - $sparepart->stok;

                // Cuma proses barang yang stok fisiknya BEDA dari stok sistem
                if ($selisih !== 0) {
                    $stokSistemLama = $sparepart->stok;

                    $sparepart->update(['stok' => $stokFisik]);

                    StockHistory::create([
                        'sparepart_id' => $sparepart->id,
                        'jenis' => 'penyesuaian',
                        'jumlah' => abs($selisih),
                        'keterangan' => 'Stock Opname: stok sistem '.$stokSistemLama.', stok fisik '.$stokFisik
                            .' ('.($selisih > 0 ? 'lebih '.$selisih : 'kurang '.abs($selisih)).')',
                    ]);

                    $hasilOpname[] = [
                        'nama' => $sparepart->nama,
                        'stok_sistem' => $stokSistemLama,
                        'stok_fisik' => $stokFisik,
                        'selisih' => $selisih,
                    ];
                }
            }
        });

        return view('admin.stock-opname-hasil', compact('hasilOpname'));
    }
}