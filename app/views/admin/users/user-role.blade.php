@extends('layouts.master')
@section('content')
@if (Session::has('message'))
    <div class="alert alert-success">{{ Session::get('message') }}</div>
@endif
<h3>Attach Roles</h3>
{{ Form::open( array('action' => array('UserController@postAttachRole',$user->id), 'class' => 'form-horizontal' , 'role' => 'forms') ) }}
  <div class="form-group">
    <label for="id-field-users" class="col-sm-2 control-label">Users</label>
    <div class="col-sm-10">
      <select id="id-field-users" name="users" class="form-control chosen-select" tabindex="-1">
          @foreach($users as $oneUser)
              <option value="{{ $oneUser->id }}" {{ ($oneUser->id === $user->id) ? "selected" : '' }}>{{{ $oneUser->getFullName().' (' .$oneUser->username . ')' }}}</option>
          @endforeach
          reach
      </select>
    </div>
  </div>
  <div class="form-group">
  <label for="id-field-roles" class="col-sm-2 control-label">Roles</label>
  <div class="col-sm-10">
    <select id="roles[]" name="roles[]" multiple class="form-control chosen-select" size="20" tabindex="0">
    @foreach($roles as $role)
          <option value="{{ $role->id }}"{{ $user->roles->contains($role->id) ? "selected" : '' }}>{{{ ucfirst($role->display_name) }}}</option>
    @endforeach
    </select>
    </div>
  </div>
  <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
            {{ Form::submit( 'Save' ,array('class' => 'btn btn-primary')) }}
            {{ link_to_route("admin.users.index","Back",null,array('class' => 'btn btn-default')) }}
      </div>
  </div>
</form>
@stop
@section('script.footer')
    @include('shared.chosen-jquery')
    @include('shared.user-role-script')
@stop
