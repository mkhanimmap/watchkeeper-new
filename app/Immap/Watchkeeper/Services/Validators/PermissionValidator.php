<?php namespace Immap\Watchkeeper\Services\Validators;

class PermissionValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
    protected static $rules = array(
        'name' => 'required|between:4,255|unique:permissions,name@id',
        'display_name' => 'required|unique:permissions,display_name@id',
    );

}
