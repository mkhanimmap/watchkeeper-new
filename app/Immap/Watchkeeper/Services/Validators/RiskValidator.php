<?php namespace Immap\Watchkeeper\Services\Validators;

class RiskValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
    protected static $rules = array(
        'location' => 'required|between:4,255',
        'description' => 'required|between:4,255',
        'risk_datetime' => 'required',
        'user_id' => 'required',
        'country_id' => 'required',
        'risklevel_id' => 'required',
        'movement_id' => 'required',
    );

}
