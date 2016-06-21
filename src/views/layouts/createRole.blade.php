@extends('panelViews::mainTemplate')
@section('page-wrapper')
    <form action="{{$formUrl}}" method="post">
        {{ csrf_field() }}

        <div class="panel panel-default">
            <div class="panel-heading"><h3>New</h3></div>
            <div class="panel-body">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">New role</div>
                        <div class="panel-body">

                            <div class="form-group">
                                <label for="name">Name</label>
                                @if($errors->has('name'))
                                    <p class="bg-danger">{{$errors->first('name')}}</p>
                                @endif
                                <input type="text" name="name" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="label">Descriptions</label>
                                @if($errors->has('label'))
                                    <p class="bg-danger">{{$errors->first('label')}}</p>
                                @endif
                                <input type="text" name="label" value="" class="form-control">
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
                                            <input name="PermissionRead[]" value="{{ $permission->id }}" type="checkbox"> Read
                                        </label>
                                        <label>
                                            <input name="PermissionCreate[]" value="{{ $permission->id }}" type="checkbox"> Create
                                        </label>
                                        <label>
                                            <input name="PermissionEdit[]" value="{{ $permission->id }}" type="checkbox"> Edit
                                        </label>
                                        <label>
                                            <input name="PermissionDelete[]" value="{{ $permission->id }}" type="checkbox"> Delete
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