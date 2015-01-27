{{ Form::textField('code','Code', isset($usergroup->code) ? $usergroup->code : '', array('class'=>'form-control', 'placeholder'=>'code' )) }}
{{ Form::textField('name','Name', isset($usergroup->name) ? $usergroup->name : '', array('class'=>'form-control', 'placeholder'=>'name' )) }}
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('class' => 'btn btn-primary')) }}
    </div>
</div>
