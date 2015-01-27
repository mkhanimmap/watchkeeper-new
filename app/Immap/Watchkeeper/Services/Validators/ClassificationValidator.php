<?php namespace Immap\Watchkeeper\Services\Validators;

class ClassificationValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
     protected static $rules = array(
         'code' => 'required|between:4,255|unique:classifications,code@id',
         'name' => 'required|between:1,255|unique:classifications,name@id',
         'group_id' => 'required|integer'
     );

}
