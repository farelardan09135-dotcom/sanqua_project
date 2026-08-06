<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\StockHistory;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Customer;

class KasirController extends Controller
{
    /**
     * Halaman utama kasir: daftar sparepart untuk dipilih ke keranjang.
     */
    public function index(Request $request)
    {
        $spareparts = Sparepart::orderBy('nama_sparepart')->get();

        return view('kasir.index', compact('spareparts'));
    }

    /**
     * Terima keranjang dari halaman index, simpan ke session,
     * lalu arahkan ke halaman detail transaksi (pilih metode bayar).
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'cart' => 'required|json',
        ]);

        $cart = json_decode($validated['cart'], true);

        if (empty($cart)) {
            return redirect()->route('kasir.index')->with('error', 'Keranjang masih kosong.');
        }

        session(['kasir.cart' => $cart]);

        return redirect()->route('kasir.detail');
    }

    /**
     * Tampilkan rincian keranjang (dari session) + form pilih metode pembayaran.
     */
    public function detail()
    {
        $cart = session('kasir.cart', []);

        if (empty($cart)) {
            return redirect()->route('kasir.index')->with('error', 'Keranjang masih kosong.');
        }

        $spareparts = Sparepart::whereIn('id', collect($cart)->pluck('id'))->get()->keyBy('id');

        $items = collect($cart)->map(function ($row) use ($spareparts) {
            $sparepart = $spareparts[$row['id']] ?? null;

            return [
                'id' => $row['id'],
                'nama' => $sparepart->nama_sparepart ?? 'Barang tidak ditemukan',
                'qty' => $row['qty'],
                'harga_satuan' => $sparepart->harga ?? 0,
                'stok' => $sparepart->stok ?? 0,
                'subtotal' => ($sparepart->harga ?? 0) * $row['qty'],
            ];
        });

        $total = $items->sum('subtotal');

        return view('kasir.detail', compact('items', 'total'));
    }

    /**
     * Finalisasi transaksi: buat baris Transaction + TransactionItem,
     * kurangi stok, catat ke Riwayat Stok (jenis: keluar),
     * lalu arahkan ke halaman Cetak Nota.
     */
    public function bayar(Request $request)
    {
        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:Cash,Transfer,QRIS',
            'catatan' => 'nullable|string',
            'cart' => 'required|json',
        ]);

        $cart = json_decode($validated['cart'], true);

        if (empty($cart)) {
            return redirect()->route('kasir.index')->with('error', 'Keranjang kosong, transaksi dibatalkan.');
        }

        $transaction = DB::transaction(function () use ($cart, $validated) {
            $spareparts = Sparepart::whereIn('id', collect($cart)->pluck('id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            foreach ($cart as $row) {
                $sparepart = $spareparts[$row['id']];
                if ($sparepart->stok < $row['qty']) {
                    abort(422, "Stok {$sparepart->nama_sparepart} tidak mencukupi.");
                }
                $total += $sparepart->harga * $row['qty'];
            }

            $transaction = Transaction::create([
                'no_transaksi' => 'TRX-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'user_id' => auth()->id(),
                'total' => $total,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($cart as $row) {
                $sparepart = $spareparts[$row['id']];

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'sparepart_id' => $sparepart->id,
                    'qty' => $row['qty'],
                    'harga_satuan' => $sparepart->harga,
                    'subtotal' => $sparepart->harga * $row['qty'],
                ]);

                $sparepart->decrement('stok', $row['qty']);

                StockHistory::create([
                    'sparepart_id' => $sparepart->id,
                    'jenis' => 'keluar',
                    'jumlah' => $row['qty'],
                    'keterangan' => 'Transaksi '.$transaction->no_transaksi,
                ]);
            }

            return $transaction;
        });

        session()->forget('kasir.cart');

        return redirect()->route('kasir.nota', $transaction);
    }

    /**
     * Tampilkan nota/struk hasil transaksi yang sudah selesai.
     */
    public function nota(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak melihat nota ini.');
        }

        $transaction->load('items.sparepart');

        return view('kasir.nota', compact('transaction'));
    }
        
    /**
     * Halaman "Cek Barang": kasir bisa mengecek ketersediaan stok
     * sparepart secara real-time, di luar alur transaksi (misal saat
     * customer bertanya sebelum memutuskan membeli).
     */
    public function cekBarang(Request $request)
    {
        $spareparts = Sparepart::query()
            ->when($request->search, fn ($q, $search) => $q->where('nama_sparepart', 'like', "%{$search}%"))
            ->orderBy('nama_sparepart')
            ->paginate(12)
            ->withQueryString();

        return view('kasir.cek-barang', compact('spareparts'));
    }

    public function kirimNota(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:100',
            'no_wa' => 'required|string|max:20',
        ]);

        $noWaBersih = preg_replace('/[^0-9]/', '', $validated['no_wa']);

        // Cari customer yang sudah ada berdasarkan no_wa, kalau belum ada buat baru
        $customer = Customer::firstOrCreate(
            ['no_wa' => $noWaBersih],
            ['nama_customer' => $validated['nama_customer']]
        );

        $transaction->update(['customer_id' => $customer->id]);

        // Format nomor WA jadi format internasional (62xxx) untuk link wa.me
        $noWaTujuan = str_starts_with($noWaBersih, '0')
            ? '62'.substr($noWaBersih, 1)
            : $noWaBersih;

        $transaction->load('items.sparepart');

        $pesan = "Nota Transaksi CV. Sanqua\n";
        $pesan .= "No. Transaksi: {$transaction->no_transaksi}\n";
        $pesan .= "Tanggal: {$transaction->created_at->format('d/m/Y H:i')}\n\n";

        foreach ($transaction->items as $item) {
            $namaBarang = $item->sparepart->nama_sparepart ?? 'Barang dihapus';
            $pesan .= "{$namaBarang} ({$item->qty}x) - Rp ".number_format($item->subtotal, 0, ',', '.')."\n";
        }

        $pesan .= "\nTotal: Rp ".number_format($transaction->total, 0, ',', '.');
        $pesan .= "\nMetode Bayar: {$transaction->metode_pembayaran}";
        $pesan .= "\n\nTerima kasih telah berbelanja di CV. Sanqua.";

        $waLink = "https://wa.me/{$noWaTujuan}?text=".urlencode($pesan);

        return redirect()->away($waLink);
    }

    /**
     * Halaman "Riwayat": daftar transaksi yang PERNAH dilakukan
     * oleh kasir yang sedang login (bukan semua transaksi seperti
     * Laporan Penjualan milik Owner).
     */
    public function riwayat(Request $request)
    {
        $transaksis = Transaction::query()
            ->where('user_id', auth()->id())
            ->when($request->search, fn ($q, $search) => $q->where('no_transaksi', 'like', "%{$search}%"))
            ->when($request->tanggal, fn ($q, $tanggal) => $q->whereDate('created_at', $tanggal))
            ->when(
                $request->bulan && !$request->tanggal, // tanggal spesifik override filter bulan
                function ($q) use ($request) {
                    [$tahun, $bulan] = explode('-', $request->bulan);
                    $q->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan);
                }
            )
            ->with('items.sparepart')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kasir.riwayat', compact('transaksis'));
    }
}