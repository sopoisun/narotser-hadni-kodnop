<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdjustmentDetail extends Model
{
    protected $fillable = ['adjustment_id', 'type', 'state', 'relation_id', 'harga', 'qty', 'keterangan'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function adjustment()
    {
        return $this->belongsTo('App\Adjustment', 'adjustment_id', 'id');
    }

    public function bahan()
    {
        return $this->belongsTo('App\Bahan', 'relation_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo('App\Produk', 'relation_id', 'id');
    }
}
