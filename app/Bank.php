<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = ['nama_bank', 'active'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function orderBayarBank()
    {
        return $this->hasMany('App\OrderBayarBank', 'bank_id', 'id');
    }

    public function saldoAccount()
    {
        return $this->hasMany('App\AccountSaldo', 'relation_id', 'id');
    }

    public function tax()
    {
        return $this->hasMany('App\BankTax', 'bank_id', 'id');
    }
}
