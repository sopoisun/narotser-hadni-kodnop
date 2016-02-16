<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }
}
