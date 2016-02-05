<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTax extends Model
{
    protected $fillable = ['order_id', 'tax_id', 'procentage'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function tax()
    {
        return $this->belongsTo('App\Tax', 'tax_id', 'id');
    }
}
