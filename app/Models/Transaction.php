<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['no_transaksi', 'user_id', 'customer_id', 'total', 'metode_pembayaran', 'catatan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getRouteKeyName()
    {
        return 'no_transaksi';
    }
}