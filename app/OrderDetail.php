<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id', 'produk_id', 'hpp', 'harga_jual', 'qty', 'use_mark_up', 'mark_up', 'note'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function detailBahan()
    {
        return $this->hasMany('App\OrderDetailBahan', 'order_detail_id', 'id');
    }

    public function detailReturn()
    {
        return $this->hasOne('App\OrderDetailReturn', 'order_detail_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo('App\Produk', 'produk_id', 'id');
    }
}
