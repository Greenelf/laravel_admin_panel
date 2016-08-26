<?php

namespace Greenelf\Panel;
trait HasRoles
{

    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('Greenelf\Panel\Role');
    }

    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     * @return boolean
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return !!$role->intersect($this->roles)->count();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        $permission = Permission::whereName($permission)->first();
        if (is_null($permission)) {
            return false;
        }
        return $this->hasRole($permission->roles);
    }

    /**
     * @param $admin_id
     * @param $controllerName
     * @param $controllerMethod
     */
    public function checkPermission($controllerName, $controllerMethod)
    {
        $permittedControllers = $this->getPermissionAccess();
        if(key_exists($controllerName, $permittedControllers)){
            if(key_exists($controllerMethod, $permittedControllers[$controllerName])){
                if($permittedControllers[$controllerName][$controllerMethod] == true){
                    return true;
                }else{
                    return false;
                }
            }
        }
        return false;
    }

    private function getPermissionAccess()
    {
        $permittedControllers = [];
        foreach ($this->roles as $role) {
            $rolePermissionAccess = $role->permissionAccess->toArray();
            foreach ($role->permissions as $permission) {
                if (!key_exists($permission->name, $permittedControllers)) {
                    $permittedControllers[$permission->name] = [];
                }
                foreach ($rolePermissionAccess as $item) {
                    if ($item['permission_id'] == $permission->id) {
                        if(key_exists('read', $permittedControllers[$permission->name])){
                            if($permittedControllers[$permission->name]['read'] != 1){
                                $permittedControllers[$permission->name]['read'] = $item['read'];
                            }
                        }else{
                            $permittedControllers[$permission->name]['read'] = $item['read'];
                        }

                        if(key_exists('create', $permittedControllers[$permission->name])){
                            if($permittedControllers[$permission->name]['create'] != 1){
                                $permittedControllers[$permission->name]['create'] = $item['create'];
                            }
                        }else{
                            $permittedControllers[$permission->name]['create'] = $item['create'];
                        }

                        if(key_exists('update', $permittedControllers[$permission->name])){
                            if($permittedControllers[$permission->name]['update'] != 1){
                                $permittedControllers[$permission->name]['update'] = $item['update'];
                            }
                        }else{
                            $permittedControllers[$permission->name]['update'] = $item['update'];
                        }

                        if(key_exists('delete', $permittedControllers[$permission->name])){
                            if($permittedControllers[$permission->name]['delete'] != 1){
                                $permittedControllers[$permission->name]['delete'] = $item['delete'];
                            }
                        }else{
                            $permittedControllers[$permission->name]['delete'] = $item['delete'];
                        }
                    }
                }
            }
        }

        return $permittedControllers;

    }

}
