<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPlace extends Model
{
    protected $fillable = ['order_id', 'place_id', 'harga'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function place()
    {
        return $this->belongsTo('App\Place', 'place_id', 'id');
    }
}
