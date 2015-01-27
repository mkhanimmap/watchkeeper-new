{{ Form::textField('name','Name', isset($role->name) ? $role->name : '', array('class'=>'form-control', 'placeholder'=>'Name' )) }}
{{ Form::textField('display_name','Display Name', isset($role->display_name) ? $role->display_name : '', array('class'=>'form-control', 'placeholder'=>'Display Name' )) }}
<div class="form-group">D
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('class' => 'btn btn-primary')) }}
    </div>
</div>
