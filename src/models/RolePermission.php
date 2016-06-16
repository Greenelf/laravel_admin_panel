<?php

namespace Greenelf\Panel;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
     //   return $this->belongsToMany(Permission::class);
    }

    /**
     * Grant the given permission to a role.
     *
     * @param  Permission $permission
     * @return mixed
     */
    public function givePermissionTo(RolePermission $permission)
    {
       // return $this->permissions()->save($permission);
    }
}
