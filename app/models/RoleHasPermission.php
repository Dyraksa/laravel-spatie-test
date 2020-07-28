<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    protected $fillable = ['permission_id','role_id'];
    public $timestamps = false;
}
