<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Produk;

class StokProduk extends Model
{
    protected $fillable = ['produk_id', 'stok'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
