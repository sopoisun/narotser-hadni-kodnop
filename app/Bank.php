<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = ['nama_bank', 'credit_card_tax'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function orderBayarBank()
    {
        return $this->hasMany('App\OrderBayarBank', 'bank_id', 'id');
    }
}
