<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $fillable = ['karyawan_id', 'keterangan', 'tanggal'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['created_at', 'updated_at', 'tanggal'];
    public static $types = [
        'bahan'     => 'Bahan',
        'produk'    => 'Produk',
    ];
    public static $states = [
        'reduction' => 'Pengurangan',
        'increase'  => 'Penambahan',
    ];

    public function detail()
    {
        return $this->hasMany('App\AdjustmentDetail', 'adjustment_id', 'id');
    }

    public function karyawan()
    {
        return $this->belongsTo('App\Karyawan', 'karyawan_id', 'id');
    }
}
