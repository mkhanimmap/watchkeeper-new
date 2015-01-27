 <fieldset>
    <div class="ui-field-contain">
        <label for="datetime">Date Time</label>
        {{ Form::jqmDateTimeLocal('datetime', isset($securityAdvisory->security_advisory_datetime) ? $securityAdvisory->dateTimeAction : $now, array('data-clear-btn' => 'true')) }}
    </div>
    <div class="ui-field-contain">
        <label for="country_id">Country </label>
        {{ Form::select('country_id', $countries , isset($country_id) ? $country_id :  Input::old('country_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="location">Location</label>
        {{ Form::text('location', isset($securityAdvisory->location) ? $securityAdvisory->location : '',array('id' => 'id-field-search-location')) }}
    </div>
    <div class="ui-field-contain">
        <label for="advice">Advice</label>
        {{ Form::textarea('advice', isset($securityAdvisory->advice) ? $securityAdvisory->advice : '') }}
    </div>
    <div class="ui-field-contain">
        <label for="description">Description</label>
        {{ Form::textarea('description', isset($securityAdvisory->description) ? $securityAdvisory->description : '') }}
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
    <div id="map-canvas" class="ui-field-contain">
    </div>
    <div id="map-canvas-control" class="ui-field-contain">
        {{ Form::textarea('geojson', isset($securityAdvisory->geojson) ? $securityAdvisory->geojson : '', array('id'=> 'id-field-geoJSON' , 'placeholder'=>'' )) }}
        {{ Form::button('Load Geojson' ,array('id'=> 'loadJsonButton' ,'class' => 'ui-btn ui-btn-inline')) }}
        {{ Form::button('Clear' ,array('id'=> 'clearmap' ,'class' => 'ui-btn ui-btn-inline')) }}
    </div>
    <div class="ui-field-contain">
        <label for="incidentType_id">Incident Types</label>
        {{ Form::select('incidentType_id', $incidentTypes , isset($incidentType_id) ? $incidentType_id : Input::old('incidentType_id')) }}
    </div>
    {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('data-theme' => 'b')) }}
</fieldset>
