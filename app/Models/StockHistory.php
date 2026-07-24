<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = ['sparepart_id', 'jenis', 'jumlah', 'keterangan'];

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}