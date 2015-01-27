<?php namespace Immap\Watchkeeper\Services\Validators;

class PoiValidator extends AbstractLaravelValidator {

     /**
     * Rules for a Poi
     * @var array
     */
    protected static $rules = array(
        'location' => 'required|between:4,255',
        'description' => 'required|min:4',
        'poi_datetime' => 'required',
        'user_id' => 'required',
        'country_id' => 'required',
        'poiType_id' => 'required',
        'image' => 'image|max:3000'
    );

}
