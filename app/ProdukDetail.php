<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdukDetail extends Model
{
    protected $fillable = ['produk_id', 'bahan_id', 'qty'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function produk()
    {
        return $this->belongsTo('App\Produk', 'produk_id', 'id');
    }

    public function bahan()
    {
        return $this->belongsTo('App\Bahan', 'bahan_id', 'id');
    }
}
