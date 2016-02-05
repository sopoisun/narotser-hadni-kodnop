<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PembelianBayar extends Model
{
    protected $fillable = ['pembelian_id', 'nominal', 'karyawan_id', 'tanggal'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['created_at', 'updated_at', 'tanggal'];

    public function pembelian()
    {
        return $this->belongsTo('App\Pembelian', 'pembelian_id', 'id');
    }

    public function karyawan()
    {
        return $this->belongsTo('App\Karyawan', 'karyawan_id', 'id');
    }
}
