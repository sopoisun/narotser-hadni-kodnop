<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountSaldo extends Model
{
    protected $fillable = ['tanggal', 'account_id', 'type', 'nominal', 'relation_id'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $dates    = ['created_at', 'updated_at', 'tanggal'];

    public function account()
    {
        return $this->belongsTo('App\Account', 'account_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo('App\Bank', 'relation_id', 'id');
    }
}
