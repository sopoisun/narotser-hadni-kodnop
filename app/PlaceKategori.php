<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaceKategori extends Model
{
    protected $fillable = ['nama', 'active'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function place()
    {
        return $this->hasMany('App\Place', 'kategori_id', 'id');
    }
}
