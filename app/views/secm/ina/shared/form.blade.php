 <fieldset>
    <div class="ui-field-contain">
        <label for="datetime">Date Time</label>
        {{ Form::jqmDateTimeLocal('datetime', isset($incident->incident_datetime) ? $incident->dateTimeAction : $now, array('data-clear-btn' => 'true')) }}
    </div>
    <div class="ui-field-contain">
        <label for="country_id">Country </label>
        {{ Form::select('country_id', $countries , isset($country_id) ? $country_id :  Input::old('country_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="location">Location</label>
        {{ Form::text('location', isset($incident->location) ? $incident->location : '',array('id' => 'id-field-search-location')) }}
    </div>
    <div class="ui-field-contain">
        <label for="description">Description</label>
        {{ Form::textarea('description', isset($incident->description) ? $incident->description : '') }}
    </div>
    <div class="ui-field-contain">
        <label for="source">Source</label>
        {{ Form::text('source', isset($incident->source) ? $incident->source : '') }}
    </div>
    <div class="ui-field-contain">
        <label for="source_grade_id">Source Grade</label>
        {{ Form::select('source_grade_id', $source_grades , isset($source_grade_id) ? $source_grade_id : Input::old('source_grade_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="injured">Injured</label>
        {{ Form::jqmNumber('injured', isset($incident->injured) ? $incident->injured : '0') }}
    </div>
    <div class="ui-field-contain">
        <label for="killed">Killed</label>
        {{ Form::jqmNumber('killed', isset($incident->killed) ? $incident->killed : '0') }}
    </div>
    <div class="ui-field-contain">
        <label for="captured">Captured</label>
        {{ Form::jqmNumber('captured', isset($incident->captured) ? $incident->captured : '0') }}
    </div>
    <div class="ui-field-contain">
        <label for="specification_location">Specification Location</label>
        <select name="specification_location" id="specification_location" data-role="slider">
                <option value="off" {{ ($specification_location == 'off') ? 'selected' : '' }}>Off</option>
                <option value="on" {{ ($specification_location == 'on') ? 'selected' : '' }}>On</option>
        </select>
    </div>
    <div id="div-pointArea_id" class="ui-field-contain hidden">
        <label for="pointArea_id">incident Area Point</label>
        {{ Form::select('pointArea_id', $pointareas , isset($pointarea_id) ? $pointarea_id : Input::old('pointArea_id')) }}
    </div>
    <div id="map-canvas" class="ui-field-contain">
    </div>
    <div id="map-canvas-control" class="ui-field-contain">
        {{ Form::textarea('geojson', isset($incident->geojson) ? $incident->geojson : '', array('id'=> 'id-field-geoJSON' , 'placeholder'=>'' )) }}
        {{ Form::button('Load Geojson' ,array('id'=> 'loadJsonButton' ,'class' => 'ui-btn ui-btn-inline')) }}
        {{ Form::button('Clear' ,array('id'=> 'clearmap' ,'class' => 'ui-btn ui-btn-inline')) }}
    </div>
    <div class="ui-field-contain">
        <label for="incident_category_id">Incident Categroies</label>
        {{ Form::select('incident_category_id', $incident_categories, isset($incident_category_id) ? $incident_category_id : Input::old('incident_category_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="incident_type_id">Incident Type</label>
        {{ Form::select('incident_type_id', $incident_types , isset($incident_type_id) ? $incident_type_id : Input::old('incident_type_id')) }}
    </div>
    {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('data-theme' => 'b')) }}
</fieldset>
@section('script.footer')

@stop
