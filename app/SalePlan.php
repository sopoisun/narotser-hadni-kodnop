<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalePlan extends Model
{
    protected $fillable = ['kode_plan', 'tanggal'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['created_at', 'updated_at', 'tanggal'];

    public function detail()
    {
        return $this->belongsTo(SalePlanDetail::class, 'produk_id', 'id');
    }
}
