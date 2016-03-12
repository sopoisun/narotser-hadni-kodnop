<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTax extends Model
{
    protected $fillable = ['bank_id', 'type', 'tax'];
    protected $hidden = ['created_at', 'updated_at'];

    public function bank()
    {
        return $this->belongsTo('App\Bank', 'bank_id', 'id');
    }
}
