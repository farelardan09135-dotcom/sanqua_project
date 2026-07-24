<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sparepart;
use App\Models\StockHistory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembelianController extends Controller
{
    /**
     * Daftar riwayat pembelian (list, bukan form input).
     */
    public function index()
    {
        $purchases = Purchase::with('supplier', 'user')
            ->latest()
            ->paginate(10);

        return view('admin.pembelian.index', compact('purchases'));
    }

    /**
     * Form untuk membuat pembelian baru.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $spareparts = Sparepart::orderBy('nama')->get();

        return view('admin.pembelian.create', compact('suppliers', 'spareparts'));
    }

    /**
     * Simpan pembelian baru: buat Purchase + PurchaseItem,
     * tambah stok barang, catat ke Riwayat Stok (jenis: masuk).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|json',
        ]);

        $items = json_decode($validated['items'], true);

        if (empty($items)) {
            return back()->with('error', 'Minimal harus ada 1 barang.');
        }

        DB::transaction(function () use ($validated, $items) {
            $total = collect($items)->sum(fn ($row) => $row['qty'] * $row['harga_beli']);

            $purchase = Purchase::create([
                'no_pembelian' => 'PB-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'total_pembelian' => $total,
            ]);

            foreach ($items as $row) {
                $subtotal = $row['qty'] * $row['harga_beli'];

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'sparepart_id' => $row['id'],
                    'qty' => $row['qty'],
                    'harga_beli' => $row['harga_beli'],
                    'subtotal' => $subtotal,
                ]);

                $sparepart = Sparepart::find($row['id']);
                $sparepart->increment('stok', $row['qty']);

                StockHistory::create([
                    'sparepart_id' => $row['id'],
                    'jenis' => 'masuk',
                    'jumlah' => $row['qty'],
                    'keterangan' => "Pembelian {$purchase->no_pembelian}",
                ]);
            }
        });

        return redirect()->route('admin.pembelian')->with('status', 'Pembelian berhasil disimpan, stok telah diperbarui.');
    }

    /**
     * Lihat detail 1 pembelian (rincian barang yang dibeli).
     */
    public function show(Purchase $pembelian)
    {
        $pembelian->load('supplier', 'user', 'items.sparepart');

        return view('admin.pembelian.show', ['purchase' => $pembelian]);
    }
}