<?php

namespace Greenelf\Panel;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'permission_role';

    protected $fillable = ['permission_id', 'role_id', 'read', 'create', 'update', 'delete'];

    //protected $primaryKey = 'role_id';

    //public $incrementing = false;

    public $timestamps = false;

    public function permission(){
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
