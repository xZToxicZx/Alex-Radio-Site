<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role_perm extends Model
{
    protected $fillable = [
        'name', 'role_id'
    ];
    public function role()
    {
        return $this->hasOne('App\Role');
    }
}
