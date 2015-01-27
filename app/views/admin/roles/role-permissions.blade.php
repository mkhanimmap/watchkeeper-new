@extends('layouts.master')
@section('content')
@if (Session::has('message'))
    <div class="alert alert-success">{{ Session::get('message') }}</div>
@endif
<h3>Attach Permissions</h3>
{{ Form::open( array('action' => array('RoleController@postAttachPermissions',$role->id), 'class' => 'form-horizontal' , 'permission' => 'forms') ) }}
  <div class="form-group">
    <label for="id-field-roles" class="col-sm-2 control-label">Roles</label>
    <div class="col-sm-10">
      <select id="id-field-roles" name="roles" class="form-control chosen-select" tabindex="-1">
          @foreach($roles as $oneRole)
              <option value="{{ $oneRole->id }}" {{ ($oneRole->id === $role->id) ? "selected" : '' }}>{{{ $oneRole->display_name }}}</option>
          @endforeach
      </select>
    </div>
  </div>
  <div class="form-group">
  <label for="id-field-permissions" class="col-sm-2 control-label">Permissions</label>
  <div class="col-sm-10">
    <select id="permissions[]" name="permissions[]" multiple class="form-control chosen-select" size="20" tabindex="0">
    <?php $oldgroup = '';?>
    @foreach($permissions as $permission)
      @if ($oldgroup === '' || $oldgroup !== $permission->group_name)
        {{ $oldgroup !== '' ? '</optgroup>' : '' }}
        <optgroup label="{{$permission->group_name}}">
        <?php $oldgroup = $permission->group_name ?>
      @endif
          <option value="{{ $permission->id }}"{{ $role->perms->contains($permission->id) ? "selected" : '' }}>{{{ ucfirst($permission->display_name) }}}</option>
      @endforeach
          </optgroup>
    </select>
    </div>
  </div>
  <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit( 'Save' ,array('class' => 'btn btn-primary')) }}
            {{ link_to_route("admin.roles.index","Back",null,array('class' => 'btn btn-default')) }}
      </div>
  </div>
</form>
@stop
@section('script.footer')
    @include('shared.chosen-jquery')
    @include('shared.role-permissions-script')
@stop
