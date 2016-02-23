<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['nama', 'kategori_id', 'harga', 'active'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function orderPlace()
    {
        return $this->hasMany('App\OrderPlace', 'place_id', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo('App\PlaceKategori', 'kategori_id', 'id');
    }
}
