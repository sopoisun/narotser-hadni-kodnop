<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokBahan extends Model
{
    protected $fillable = ['bahan_id', 'stok'];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id', 'id');
    }
}
