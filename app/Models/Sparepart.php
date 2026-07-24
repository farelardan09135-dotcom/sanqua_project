<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel `spareparts` — data barang/sparepart di Inventory.
 */
class Sparepart extends Model
{
    /**
     * Kolom yang boleh diisi lewat mass assignment
     * (Sparepart::create([...]) di SparepartController).
     */
    protected $fillable = ['nama', 'kategori', 'stok', 'harga'];

    /**
     * Relasi one-to-many: satu sparepart bisa muncul di banyak
     * baris Detail Transaksi (transaction_items) yang berbeda.
     */
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Accessor: menghitung label status stok secara otomatis
     * berdasarkan angka $stok, tanpa perlu kolom tambahan di database.
     *
     * Bisa dipanggil di Blade sebagai properti biasa: $sparepart->status
     */
    public function getStatusAttribute(): string
    {
        if ($this->stok == 0) return 'Habis';
        if ($this->stok < 10) return 'Menipis';
        return 'Tersedia';
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}