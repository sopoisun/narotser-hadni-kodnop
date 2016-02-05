<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBayar extends Model
{
    protected $fillable = ['order_id', 'karyawan_id', 'diskon', 'bayar', 'type_bayar'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function karyawan()
    {
        return $this->belongsTo('App\Karyawan', 'karyawan_id', 'id');
    }
}
