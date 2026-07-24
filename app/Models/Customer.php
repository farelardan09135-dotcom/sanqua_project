<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['nama_customer', 'no_wa'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}