<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['type', 'procentage'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function orderTax()
    {
        return $this->hasOne('App\OrderTax', 'tax_id', 'id');
    }
}
