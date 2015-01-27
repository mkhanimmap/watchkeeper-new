{{ Form::textField('name','Name', isset($pointarea->name) ? $pointarea->name : '', array('class'=>'form-control', 'placeholder'=>'Name' )) }}
{{ Form::textareaField('description','Description', isset($pointarea->description) ? $pointarea->description : '', array('class'=>'form-control', 'placeholder'=>'Description' )) }}
<input class="form-control" id="id-field-search-location" placeholder="Search Location" name="search-location" type="text" value="" style="z-index: 0; position: absolute; left: 42px; top: 0px;">
<div class="form-group">
    <label for="id-field-map" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
      <div id="map-canvas" class="map-canvas">
      </div>
    </div>
</div>
{{ Form::textareaField('geoJSON','Geo JSON', isset($pointarea->geojson) ? $pointarea->geojson : '', array('class'=>'form-control', 'placeholder'=>'' )) }}
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {{ Form::button('Load Geojson' ,array('id'=> 'loadJsonButton' ,'class' => 'btn btn-default')) }}
        {{ Form::button('Clear' ,array('id'=> 'clearmap' ,'class' => 'btn btn-default')) }}
        {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('class' => 'btn btn-primary')) }}
    </div>
</div>
