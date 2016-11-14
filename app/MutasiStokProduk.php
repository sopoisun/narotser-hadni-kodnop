<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Produk;

class MutasiStokProduk extends Model
{
    protected $fillable = ['produk_id', 'stok', 'tanggal'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
}
