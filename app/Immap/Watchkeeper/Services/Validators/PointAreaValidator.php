<?php namespace Immap\Watchkeeper\Services\Validators;

class PointAreaValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
    protected static $rules = array(
        'name' => 'required|unique:pointareas,name@id',
    );

}
