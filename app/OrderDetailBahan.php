<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailBahan extends Model
{
    protected $fillable = ['order_detail_id', 'bahan_id', 'harga', 'qty', 'satuan'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function orderDetail()
    {
        return $this->belongsTo('App\OrderDetail', 'order_detail_id', 'id');
    }

    public function bahan()
    {
        return $this->belongsTo('App\Bahan', 'bahan_id', 'id');
    }
}
