@extends('panelViews::mainTemplate')
@section('page-wrapper')
    <form action="{{$formUrl}}" method="post">
        {{ csrf_field() }}
        <input type="text" name="role_id" value="{{ $role->id or ''}}" hidden>
        <div class="panel panel-default">
            <div class="panel-heading"><h3>Role edit</h3></div>
            <div class="panel-body">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Edit role</div>
                        <div class="panel-body">

                            <div class="form-group">
                                <label for="name">Name</label>
                                @if($errors->has('name'))
                                    <p class="bg-danger">{{$errors->first('name')}}</p>
                                @endif
                                <input type="text" name="name" value="{{ $role->name }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="label">Descriptions</label>
                                @if($errors->has('label'))
                                    <p class="bg-danger">{{$errors->first('label')}}</p>
                                @endif
                                <input type="text" name="label" value="{{ $role->label}}" class="form-control">
                            </div>
                            <label for="rolePermission">Permissions</label>
                            @foreach($permissions as $permission)
                                <div class="form-group">
                                    @if($errors->has('rolePermission'))
                                        <p class="bg-danger">{{$errors->first('rolePermission')}}</p>
                                    @endif
                                    <div class="checkbox">
                                        <label for="rolePermission">{{ $permission->name }}</label>
                                        <input name="permissions[]" hidden value="{{ $permission->id }}">
                                        <label>
                                            <input name="PermissionRead[]" value="{{ $permission->id }}"
                                                   @if(array_key_exists($permission->id,$permissionAccessArray))
                                                        @if($permissionAccessArray[$permission->id]['read'])
                                                        checked
                                                        @endif
                                                   @endif
                                                   type="checkbox"> Read
                                        </label>
                                        <label>
                                            <input name="PermissionCreate[]" value="{{ $permission->id }}"
                                                   @if(array_key_exists($permission->id,$permissionAccessArray))
                                                        @if($permissionAccessArray[$permission->id]['create'])
                                                        checked
                                                        @endif
                                                   @endif
                                                   type="checkbox"> Create
                                        </label>
                                        <label>
                                            <input name="PermissionEdit[]" value="{{ $permission->id }}"
                                                   @if(array_key_exists($permission->id,$permissionAccessArray))
                                                         @if($permissionAccessArray[$permission->id]['update'])
                                                        checked
                                                        @endif
                                                   @endif
                                                   type="checkbox"> Edit
                                        </label>
                                        <label>
                                            <input name="PermissionDelete[]" value="{{ $permission->id }}"
                                                   @if(array_key_exists($permission->id,$permissionAccessArray))
                                                        @if($permissionAccessArray[$permission->id]['delete'])
                                                        checked
                                                        @endif
                                                   @endif
                                                   type="checkbox"> Delete
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center">
                                <button class="btn btn-success" type="submit">Зберегти</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


@stop