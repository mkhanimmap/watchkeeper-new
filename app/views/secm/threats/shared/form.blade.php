 <fieldset>
    <div class="ui-field-contain">
        <label for="datetime">Date Time</label>
        {{ Form::jqmDateTimeLocal('datetime', isset($threat->threat_datetime) ? $threat->dateTimeAction : $now, array('data-clear-btn' => 'true')) }}
    </div>
    <div class="ui-field-contain">
        <label for="country_id">Country </label>
        {{ Form::select('country_id', $countries , isset($country_id) ? $country_id :  Input::old('country_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="location">Location</label>
        {{ Form::text('location', isset($threat->location) ? $threat->location : '',array('id' => 'id-field-search-location')) }}
    </div>
    <div class="ui-field-contain">
        <label for="title">Title</label>
        {{ Form::text('title', isset($threat->title) ? $threat->title : '') }}
    </div>
    <div class="ui-field-contain">
        <label for="description">Description</label>
        {{ Form::textarea('description', isset($threat->description) ? $threat->description : '') }}
    </div>
    <div class="ui-field-contain">
        <label for="advice">Advice</label>
        {{ Form::textarea('advice', isset($threat->advice) ? $threat->advice : '') }}
    </div>
    <div class="ui-field-contain">
        <label for="source">Source</label>
        {{ Form::text('source', isset($threat->source) ? $threat->source : '') }}
    </div>
    <div class="ui-field-contain">
        <label for="source_grade_id">Source Grade</label>
        {{ Form::select('source_grade_id', $source_grades , isset($source_grade_id) ? $source_grade_id : Input::old('source_grade_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="injured">Injured</label>
        {{ Form::jqmNumber('injured', isset($threat->injured) ? $threat->injured : '0') }}
    </div>
    <div class="ui-field-contain">
        <label for="killed">Killed</label>
        {{ Form::jqmNumber('killed', isset($threat->killed) ? $threat->killed : '0') }}
    </div>
    <div class="ui-field-contain">
        <label for="captured">Captured</label>
        {{ Form::jqmNumber('captured', isset($threat->captured) ? $threat->captured : '0') }}
    </div>
    <div class="ui-field-contain">
        <label for="specification_location">Specification Location</label>
        <select name="specification_location" id="specification_location" data-role="slider">
                <option value="off" {{ ($specification_location == 'off') ? 'selected' : '' }}>Off</option>
                <option value="on" {{ ($specification_location == 'on') ? 'selected' : '' }}>On</option>
        </select>
    </div>
    <div id="div-pointArea_id" class="ui-field-contain hidden">
        <label for="pointArea_id">Threat Area Point</label>
        {{ Form::select('pointArea_id', $pointareas , isset($pointarea_id) ? $pointarea_id : Input::old('pointArea_id')) }}
    </div>
    <div id="map-canvas" class="ui-field-contain">
    </div>
    <div id="map-canvas-control" class="ui-field-contain">
        {{ Form::textarea('geojson', isset($threat->geojson) ? $threat->geojson : '', array('id'=> 'id-field-geoJSON' , 'placeholder'=>'' )) }}
        {{ Form::button('Load Geojson' ,array('id'=> 'loadJsonButton' ,'class' => 'ui-btn ui-btn-inline')) }}
        {{ Form::button('Clear' ,array('id'=> 'clearmap' ,'class' => 'ui-btn ui-btn-inline')) }}
    </div>
    <div class="ui-field-contain">
        <label for="threat_category_id">Threat Categroies</label>
        {{ Form::select('threat_category_id', $threat_categories, isset($threat_category_id) ? $threat_category_id : Input::old('threat_category_id')) }}
    </div>
    <div class="ui-field-contain">
        <label for="threat_type_id">Threat Type</label>
        {{ Form::select('threat_type_id', $threat_types , isset($threat_type_id) ? $threat_type_id : Input::old('threat_type_id')) }}
    </div>
    {{ Form::submit( isset($buttonText) ? $buttonText : 'Create' ,array('data-theme' => 'b')) }}
</fieldset>
@section('script.footer')

@stop
