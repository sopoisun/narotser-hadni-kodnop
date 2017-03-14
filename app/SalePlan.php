<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalePlan extends Model
{
    protected $fillable = ['kode_plan'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function detail()
    {
        return $this->belongsTo(App\SalePlanDetail::class, 'produk_id', 'id');
    }
}
