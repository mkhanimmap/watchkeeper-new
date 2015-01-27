{{ Form::textField('code','Code', isset($classification->code) ? $classification->code : '', array('class'=>'form-control', 'placeholder'=>'Code' )) }}
{{ Form::textField('name','Name', isset($classification->name) ? $classification->name : '', array('class'=>'form-control', 'placeholder'=>'Name' )) }}
<div class="form-group">
  <label for="group_id" class="col-sm-2 control-label">Category</label>
  <div class="col-sm-10">
    <select id="group_id" name="group_id" class="form-control chosen-select">
        @foreach($classificationTypes as $classificationTypeKey => $classificationTypeValue)
            <option value="{{ $classificationTypeKey }}" {{ (
            isset($classification) && $classification->group_id == $classificationTypeKey)
            ? "selected" : '' }}>{{{ ucwords(strtolower($classificationTypeValue)) }}}</option>
        @endforeach

    </select>
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
