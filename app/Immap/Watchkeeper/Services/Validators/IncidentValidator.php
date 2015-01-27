<?php namespace Immap\Watchkeeper\Services\Validators;

class IncidentValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
     protected static $rules = array(
         'location' => 'required|between:4,255',
         'description' => 'required|min:4',
         'source' => 'max:255',
         'incident_datetime' => 'required',
         'user_id' => 'required',
         'country_id' => 'required',
         'incident_type_id' => 'required',
         'source_grade_id' => 'required',
         'incident_category_id' => 'required',
     );

}
