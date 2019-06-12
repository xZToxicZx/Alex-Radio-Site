<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'default', 'priority'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function perms()
    {
        return $this->hasMany(role_perm::class);
    }
}
