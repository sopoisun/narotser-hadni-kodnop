<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $fillable = ['nama', 'no_hp', 'alamat', 'jabatan', 'user_id'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function pembelian()
    {
        return $this->hasOne('App\Pembelian', 'karyawan_id', 'id');
    }

    public function pembelianBayar()
    {
        return $this->hasOne('App\PembelianBayar', 'karyawan_id', 'id');
    }

    public function adjustment()
    {
        return $this->hasOne('App\Adjustment', 'karyawan_id', 'id');
    }

    public function order()
    {
        return $this->hasOne('App\Order', 'karyawan_id', 'id');
    }

    public function orderBayar()
    {
        return $this->hasMany('App\OrderBayar', 'karyawan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
