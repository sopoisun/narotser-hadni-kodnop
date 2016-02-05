<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    protected $fillable = ['pembelian_id', 'relation_id', 'qty', 'satuan', 'harga', 'stok', 'type'];
    protected $hidden   = ['created_at', 'updated_at'];
    public static $types = [
        'bahan'     => 'Bahan',
        'produk'    => 'Produk',
    ];

    public function pembelian()
    {
        return $this->belongsTo('App\Pembelian', 'pembelian_id', 'id');
    }

    public function bahan()
    {
        return $this->belongsTo('App\Bahan', 'relation_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo('App\Produk', 'relation_id', 'id');
    }
}
