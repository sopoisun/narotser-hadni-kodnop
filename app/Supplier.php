<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['nama', 'no_hp', 'alamat', 'nama_perusahaan', 'active'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function produk()
    {
        return $this->hasOne('App\Produk', 'supplier_id', 'id');
    }

    public function pembelian()
    {
        return $this->hasOne('App\Pembelian', 'supplier_id', 'id');
    }
}
