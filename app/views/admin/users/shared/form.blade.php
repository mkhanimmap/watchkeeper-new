@if ($action === 'create')
    {{ Form::textField('username','Username', isset($user->username) ? $user->username : '', array('class'=>'form-control', 'placeholder'=>'Username between 3,20')) }}
@else
    <div class="form-group">
        {{ Form::label('username','Username',array('class' => 'col-sm-2 control-label') )}}
         <div class="col-sm-10">
            {{ Form::hidden('username', $usernameEncrypt) }}
            <h4> {{{ isset($user->username) ? $user->username : '' }}} </h4>
        </div>
    </div>
@endif
@if ($action === 'create')
    {{ Form::textField('email','E-mail', isset($user->email) ? $user->email : '', array('class'=>'form-control', 'placeholder'=>'example@example.com' )) }}
@else
    <div class="form-group">
        {{ Form::label('email','E-mail',array('class' => 'col-sm-2 control-label') )}}
            <div class="col-sm-10">
                {{ Form::hidden('email', $emailEncrypt) }}
                <h4> {{{ isset($user->email) ? $user->email : '' }}} </h4>
            </div>
    </div>
@endif
{{ Form::textField('firstname','First Name', isset($user->firstname) ? $user->firstname : '', array('class'=>'form-control', 'placeholder'=>'First Name')) }}
{{ Form::textField('middlename','Middle Name', isset($user->middlename) ? $user->middlename : '', array('class'=>'form-control', 'placeholder'=>'Middle Name')) }}
{{ Form::textField('lastname','Last Name', isset($user->lastname) ? $user->lastname : '', array('class'=>'form-control', 'placeholder'=>'Last Name')) }}
@if (isset($buttonText) && $buttonText != "Create")
<div class="form-group">
  <label for="id-field-status" class="col-sm-2 control-label">Status</label>
  <div class="col-sm-10">
    <select id="id-field-status" name="status" class="form-control chosen-select">
            <option value="active" {{ (boolean)$user->active === true ? 'selected' : ''}} >Active</option>
            <option value="inactive" {{ (boolean)$user->active === false ? 'selected' : ''}}>Inactive</option>
    </select>
  </div>
</div>
@endif
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('class' => 'btn btn-primary')) }}
    </div>
</div>
