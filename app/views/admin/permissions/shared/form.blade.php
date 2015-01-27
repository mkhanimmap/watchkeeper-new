{{ Form::textField('name','Name', isset($permission->name) ? $permission->name : '', array('class'=>'form-control', 'placeholder'=>'Name' )) }}
{{ Form::textField('display_name','Display Name', isset($permission->display_name) ? $permission->display_name : '', array('class'=>'form-control', 'placeholder'=>'Display Name' )) }}
{{ Form::textField('group_name','Group Name', isset($permission->group_name) ? $permission->group_name : '', array('class'=>'form-control', 'placeholder'=>'Group Name' )) }}
{{-- Form::textField('action_name','Action', isset($permission->action_name) ? $permission->action_name : '', array('class'=>'form-control', 'placeholder'=>'Action' )) --}}
<div class="form-group">
  <label for="action_name2" class="col-sm-2 control-label">Action</label>
  <div class="col-sm-10">
    {{
        Form::select('action_name',
        $routes_list , isset($permission->action_name) ? $permission->action_name : Input::old('action_name'),
        array ('class' => 'form-control chosen-select'))
    }}
  </div>
</div>


<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('class' => 'btn btn-primary')) }}
    </div>
</div>
@section('script.footer')
  @include('shared.chosen-jquery')
@stop
