<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['nama_akun', 'data_state', 'type', 'relation', 'can_edit'];
    protected $hidden   = ['created_at', 'updated_at'];
    public static $data_states  = [
        'input' => 'Input Manual',
        'auto'  => 'Otomatis',
    ];
    public static $types        = [
        'debet'  => 'Debet',
        'kredit' => 'Kredit',
    ];
    public static $can_edit     = [
        'Ya'    => 'Ya',
        'Tidak' => 'Tidak',
    ];

    public function saldo()
    {
        return $this->hasMany('App\AccountSaldo', 'account_id', 'id');
    }
}
