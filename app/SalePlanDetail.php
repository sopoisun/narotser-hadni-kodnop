<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalePlanDetail extends Model
{
    protected $fillable = ['sale_plan_id', 'produk_id', 'qty'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function salePlan()
    {
        return $this->belongsTo(App\SalePlan::class, 'sale_plan_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(App\Produk::class, 'produk_id', 'id');
    }
}
