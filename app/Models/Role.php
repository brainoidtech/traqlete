<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{
    // use SoftDeletes;

   protected $table = 'roles';

    protected $fillable = [
        'id',
        'role_name',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'role_id', 'id');
    }

    public function getUserRoles(){
        return self::select('id','role_name')->whereNotIn('id', [1,3,4,5,6])->get();
    }

    public function getAdminUserRoles(){
        return self::select('id','role_name')->whereNotIn('id', [2,3])->get();
    }

   
}
