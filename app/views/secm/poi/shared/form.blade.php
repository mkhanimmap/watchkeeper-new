 <fieldset>
    <div class="ui-field-contain">
        <label for="datetime">Date Time</label>
        {{ Form::jqmDateTimeLocal('datetime', isset($poi->poi_datetime) ? $poi->dateTimeAction : $now, array('data-clear-btn' => 'true')) }}
    </div>
    <div class="ui-field-contain">
        <label for="country_id">Country </label>
        {{ Form::select('country_id', $countries , isset($country_id) ? $country_id :  Input::old('country_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="location">Location</label>
        {{ Form::text('location', isset($poi->location) ? $poi->location : '',array('id' => 'id-field-search-location')) }}
    </div>
    <div class="ui-field-contain">
        <label for="description">Description</label>
        {{ Form::textarea('description', isset($poi->description) ? $poi->description : '') }}
    </div>
    <div class="ui-field-contain">
        <label for="poiType_id">Point of Incident Types</label>
        {{ Form::select('poiType_id', $poiTypes , isset($poiType_id) ? $poiType_id : Input::old('poiType_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="immap_asset">Immap asset</label>
        <select name="immap_asset" id="immap_asset" data-role="slider">
                <option value="yes" {{ ($immap_asset == 'yes') ? 'selected' : '' }}>Off</option>
                <option value="no" {{ ($immap_asset == 'no') ? 'selected' : '' }}>On</option>
        </select>
    </div>
    <div class="ui-field-contain">
        <label for="alert">Alert if an incident occurs nearby ?</label>
        <select name="alert" id="alert" data-role="slider">
                <option value="yes" {{ ($alert == 'yes') ? 'selected' : '' }}>Off</option>
                <option value="no" {{ ($alert == 'no') ? 'selected' : '' }}>On</option>
        </select>
    </div>
    <div class="ui-field-contain">
        <label for="distance_km">distance (km)</label>
        {{ Form::jqmNumber('distance_km', isset($poi->distance_km) ? $poi->distance_km : '0') }}
    </div>
    <div class="ui-field-contain">
        <label for="specification_location">Specification Location</label>
        <select name="specification_location" id="specification_location" data-role="slider">
                <option value="off" {{ ($specification_location == 'off') ? 'selected' : '' }}>Off</option>
                <option value="on" {{ ($specification_location == 'on') ? 'selected' : '' }}>On</option>
        </select>
    </div>
    <div id="div-pointArea_id" class="ui-field-contain hidden">
        <label for="pointArea_id">Risk Movement Area Point</label>
        {{ Form::select('pointArea_id', $pointareas , isset($pointarea_id) ? $pointarea_id : Input::old('pointArea_id')) }}
    </div>

    <button id="chooseFile">Choose file</button>
    <div class="hidden">
        <input type="file" data-clear-btn="false" name="image" accept="image/*" capture>
    </div>

    {{ HTML::image(isset($img_path) ? $img_path : '', '', array('id'=> 'img', 'height'=> '300' , 'width' =>'200')) }}

    <div id="map-canvas" class="ui-field-contain">
    </div>
    <div id="map-canvas-control" class="ui-field-contain">
        {{ Form::textarea('geojson', isset($poi->geojson) ? $poi->geojson : '', array('id'=> 'id-field-geoJSON' , 'placeholder'=>'' )) }}
        {{ Form::button('Load Geojson' ,array('id'=> 'loadJsonButton' ,'class' => 'ui-btn ui-btn-inline')) }}
        {{ Form::button('Clear' ,array('id'=> 'clearmap' ,'class' => 'ui-btn ui-btn-inline')) }}
    </div>
    {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('data-theme' => 'b')) }}
</fieldset>
