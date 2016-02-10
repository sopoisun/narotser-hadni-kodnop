<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'display'];
    protected $hidden   = ['created_at', 'updated_at'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function users()
    {
        return $this->hasManyThrough(User::class, Role::class);
    }
}
