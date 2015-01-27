<?php namespace Immap\Watchkeeper\Services\Validators;

class RoleValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
    protected static $rules = array(
        'name' => 'required|between:4,255|unique:roles,name@id',
        'display_name' => 'required|unique:roles,display_name@id',
    );

}
