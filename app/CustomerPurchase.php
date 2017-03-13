<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerPurchase extends Model
{
    protected $fillable = ['customer_id', 'visit', 'purchase'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function member()
    {
        return $this->belongsTo(App\Customer::class, 'customer_id', 'id');
    }
}
