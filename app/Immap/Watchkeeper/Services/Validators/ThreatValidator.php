<?php namespace Immap\Watchkeeper\Services\Validators;

class ThreatValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
     protected static $rules = array(
         'location' => 'required|between:4,255',
         'title' => 'required|min:4,255',
         'description' => 'required|min:4',
         'advice' => 'required|min:4',
         'source' => 'max:255',
         'threat_datetime' => 'required',
         'user_id' => 'required',
         'country_id' => 'required',
         'threat_type_id' => 'required',
         'source_grade_id' => 'required',
         'threat_category_id' => 'required',
     );

}
