{{ Form::textField('name','Country', isset($country->name) ? $country->name : '', array('class'=>'form-control', 'placeholder'=>'Country' )) }}
{{ Form::textField('code_a3','Code A3', isset($country->code_a3) ? $country->code_a3 : '', array('class'=>'form-control', 'placeholder'=>'A3' )) }}
{{ Form::textField('code_a2','Code A2', isset($country->code_a2) ? $country->code_a2 : '', array('class'=>'form-control', 'placeholder'=>'A2' )) }}
{{ Form::selectField('active','Status', $status_list , isset($country->active) ? (int)$country->active : (int)Input::old('active')) }}
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('class' => 'btn btn-primary')) }}
    </div>
</div>
