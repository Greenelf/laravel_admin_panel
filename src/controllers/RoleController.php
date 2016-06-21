<?php

namespace Greenelf\Panel;

use Illuminate\Support\Facades\Request;

class RoleController extends CrudController
{
    public function all($entity)
    {
        parent::all($entity);

        $this->filter = \DataFilter::source(Role::with('permissions'));
        $this->filter->add('id', 'ID', 'text');
        $this->filter->add('name', 'Name', 'text');
        $this->filter->submit('search');
        $this->filter->reset('reset');
        $this->filter->build();

        $this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID', true)->style("width:100px");
        $this->grid->add('name', 'Name')->style('width:100px');
        $this->grid->add('label', 'Description');
        $this->grid->add('{{ implode(", ", $permissions->lists("name")->all()) }}', 'Permissions');


        $this->addStylesToGrid();

        return $this->returnView();
    }

    public function edit($entity)
    {
        /* parent::edit($entity);

         $this->edit = \DataEdit::source(new Role());

         $helpMessage = \Lang::get('panel::fields.roleHelp');

         $this->edit->label('Edit Role');
         $this->edit->link("rapyd-demo/filter", "Role", "TR")->back();
         $this->edit->add('name', 'Name', 'text')->rule('required');
         $this->edit->add('label', 'Description', 'text')->rule('required');
         $this->edit->add('permissions', 'Permissions', 'checkboxgroup')->options(Permission::lists('name', 'id')->all());
        /* $per = Permission::all();
         foreach( $per as $permission){
             $this->edit->add($permission->name, $permission->name, 'checkbox');
         }
 */
        //$this->edit->add('permission_role_read', 'Permissions', 'checkboxgroup')->options([ 1=> 'read'],[ 1 => 'read']);

        /*  $this->edit->saved(function () use ($entity) {
              $this->edit->message(\Lang::get('panel::fields.dataSavedSuccessfull'));
              $this->edit->link('panel/Permission/all', \Lang::get('panel::fields.back'));
              //dd($this->edit->model);
          });

          $this->addHelperMessage($helpMessage);

          return $this->returnEditView();
        */
        //$request = \App\Http\Requests\Request::capture();
        if (Request::method() == 'GET') {
            if(Request::input('modify') != null){
                $role_id = Request::input('modify');
            }elseif(Request::input('show') != null){
                $role_id = Request::input('show');
            }elseif(Request::input('delete') != null){
                $role_id = Request::input('delete');
                $role = Role::find($role_id);
                $role->delete();
                return redirect(url('/panel/' . $entity . '/all'));
            }else{
                $permissions = Permission::all();
                return view('vendor.panelViews.layouts.createRole', [
                    'permissions' => $permissions,
                    'formUrl' => $this->getFormUrl($entity),
                ]);
            }
            $role = Role::find($role_id);
            if ($role) {
                if($role->permissionAccess->count() !== 0){
                    foreach($role->permissionAccess as $row){
                        $permissionAccessArray[$row->permission_id]['read'] = $row->read;
                        $permissionAccessArray[$row->permission_id]['create'] = $row->create;
                        $permissionAccessArray[$row->permission_id]['update'] = $row->update;
                        $permissionAccessArray[$row->permission_id]['delete'] = $row->delete;
                    }
                }else{
                    $permissionAccessArray = [];
                }

                $permissions = Permission::all();
                return view('vendor.panelViews.layouts.editRole', [
                    'role' => $role,
                    'permissionAccessArray' => $permissionAccessArray,
                    'permissions' => $permissions,
                    'formUrl' => $this->getFormUrl($entity),
                ]);
            } else {
                $permissions = Permission::all();
                return view('vendor.panelViews.layouts.createRole', [
                    'permissions' => $permissions,
                    'formUrl' => $this->getFormUrl($entity),
                ]);
            }
        }
        if (Request::method() == 'POST') {
            $allInput = Request::all();
            if(isset($allInput['role_id'])){
                $role = Role::firstOrCreate(['id' => $allInput['role_id']]);
            }else{
                $role = new Role();
            }

            $role->name = $allInput['name'];
            $role->label = $allInput['label'];
            $role->save();
            foreach ($allInput['permissions'] as $rolePermission) {
                $newRolePermission = RolePermission::firstOrNew(['permission_id' => $rolePermission, 'role_id' => $role->id]);
                $newRolePermission->permission_id = (int)$rolePermission;
                $newRolePermission->role_id = $role->id;
                if (isset($allInput['PermissionRead']) and in_array($rolePermission, $allInput['PermissionRead'])) {
                    $newRolePermission->read = 1;
                }else{
                    $newRolePermission->read = 0;
                }
                if (isset($allInput['PermissionCreate']) and in_array($rolePermission, $allInput['PermissionCreate'])) {
                    $newRolePermission->create = 1;
                }else{
                    $newRolePermission->create = 0;
                }
                if (isset($allInput['PermissionEdit']) and in_array($rolePermission, $allInput['PermissionEdit'])) {
                    $newRolePermission->update = 1;
                }else{
                    $newRolePermission->update = 0;
                }
                if (isset($allInput['PermissionDelete']) and in_array($rolePermission, $allInput['PermissionDelete'])) {
                    $newRolePermission->delete = 1;
                }else{
                    $newRolePermission->delete = 0;
                }
                $newRolePermission->save();
            }
            return redirect(url('/panel/' . $entity . '/all'));
        }

    }

    private function getFormUrl($entity)
    {
        return '/panel/' . $entity . '/edit';
    }
}
