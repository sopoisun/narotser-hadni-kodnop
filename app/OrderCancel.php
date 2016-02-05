<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCancel extends Model
{
    protected $fillable = ['order_id', 'tanggal', 'keterangan'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['created_at', 'updated_at', 'tanggal'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }
}
