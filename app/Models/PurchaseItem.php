<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = ['purchase_id', 'sparepart_id', 'qty', 'harga_beli', 'subtotal'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}