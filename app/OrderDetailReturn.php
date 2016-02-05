<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetailReturn extends Model
{
    protected $fillable = ['order_detail_id', 'qty'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function orderDetail()
    {
        return $this->belongsTo('App\OrderDetail', 'order_detail_id', 'id');
    }
}
