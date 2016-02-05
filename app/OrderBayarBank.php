<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderBayarBank extends Model
{
    protected $fillable = ['order_id', 'bank_id', 'tax_procentage'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Bank', 'bank_id', 'id');
    }
}
