<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['title_faktur', 'alamat_faktur', 'telp_faktur', 'init_kode', 'laba_procentage_warning'];
    protected $hidden   = ['created_at', 'updated_at'];
}
