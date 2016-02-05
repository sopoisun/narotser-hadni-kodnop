<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['kode', 'nama', 'no_hp', 'alamat', 'keterangan'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function order()
    {
        return $this->hasMany('App\Order', 'customer_id', 'id');
    }
}
