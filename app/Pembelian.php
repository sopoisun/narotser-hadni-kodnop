<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $fillable = ['supplier_id', 'karyawan_id', 'tanggal'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['created_at', 'updated_at', 'tanggal'];

    public function detail()
    {
        return $this->hasMany('App\PembelianDetail', 'pembelian_id', 'id');
    }

    public function bayar()
    {
        return $this->hasMany('App\PembelianBayar', 'pembelian_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id');
    }

    public function karyawan()
    {
        return $this->belongsTo('App\Karyawan', 'karyawan_id', 'id');
    }
}
