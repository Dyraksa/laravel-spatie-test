<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
class ModelHasRoles extends Model
{
    protected $fillable = ['role_id','model_type','model_id'];
    public $timestamps = false;

    public function role(){
        return $this->belongsTo(Role::class);
    }
}
