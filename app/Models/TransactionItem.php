<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel `transaction_items` — tabel Detail Transaksi.
 * Junction table yang menghubungkan Transaction <-> Sparepart,
 * menyimpan qty, harga saat transaksi, dan subtotal per baris.
 */
class TransactionItem extends Model
{
    /**
     * Kolom yang boleh diisi lewat mass assignment.
     */
    protected $fillable = ['transaction_id', 'sparepart_id', 'qty', 'harga_satuan', 'subtotal'];

    /**
     * Relasi many-to-one: baris detail ini milik satu transaksi/struk tertentu.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi many-to-one: baris detail ini merujuk ke satu sparepart tertentu.
     */
    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}