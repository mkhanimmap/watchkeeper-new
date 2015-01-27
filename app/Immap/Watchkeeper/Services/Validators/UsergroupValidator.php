<?php namespace Immap\Watchkeeper\Services\Validators;

class UsergroupValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
    protected static $rules = array(
        'name' => 'required|unique:usergroups,name@id',
        'code' => 'required|unique:usergroups,code@id',
    );
}
