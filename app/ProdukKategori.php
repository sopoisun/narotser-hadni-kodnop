<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdukKategori extends Model
{
    protected $fillable = ['nama', 'active'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function produk()
    {
        return $this->hasOne('App\Produk', 'produk_kategori_id', 'id');
    }
}
