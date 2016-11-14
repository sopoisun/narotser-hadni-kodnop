<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Bahan;

class MutasiStokBahan extends Model
{
    protected $fillable = ['produk_id', 'stok', 'tanggal'];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id', 'id');
    }
}
