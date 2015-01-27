<?php namespace Immap\Watchkeeper\Services\Validators;

class SecurityAdvisoryValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
    protected static $rules = array(
        'location' => 'required|between:4,255',
        'description' => 'required|min:4',
        'advice' => 'required|min:4',
        'security_advisory_datetime' => 'required',
        'user_id' => 'required',
        'country_id' => 'required',
        'incidentType_id' => 'required',
    );

}
