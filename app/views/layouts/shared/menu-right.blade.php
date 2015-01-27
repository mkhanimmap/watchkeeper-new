@if (isset($user) && $user->roles->count() > 1)
  <li id="changeRoles-dropdown" class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Change Roles <b class="caret"></b></a>
    <ul id="changeRoles-dropdown-menu" name="changeRoles-dropdown-menu" class="dropdown-menu">
      @foreach($user->roles->all() as $role)
          <li {{ $role->id == Session::get('current_role_id') ? 'class="active"' : '' }}>{{ link_to('change-role/'.$user->id.'/'.$role->id,$role->display_name) }}</li>
      @endforeach
    </ul>
  </li>
@endif
@if (Auth::guest())
<li id="navLogin" class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown">Login <b class="caret"></b></a>
  <div id="fromLogin-dropdown-menu" name="fromLogin-dropdown-menu" class="dropdown-menu" style="padding:17px;">
    {{ Form::open( array('route' => 'auth.login.post', 'class' => 'form')) }}
      <input class="form-control-dropdown" id="id-username-name" placeholder="Username" name="username" type="text" value="">
      <input class="form-control-dropdown" id="id-password-name" placeholder="Password" name="password" type="password" value="">
      {{ Form::submit( 'Login' ,array('class' => 'btn btn-xs btn-login')) }}
    {{ Form::close() }}
  </div>
  </li>
@else
  <li>{{ HTML::linkRoute('auth.logout', 'Log out')}}</li>
@endif


