<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMerge extends Model
{
    protected $fillable = ['order_id', 'to_order_id'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function orderRef(){
        return $this->belongsTo('App\Order', 'to_order_id', 'id');
    }
}
