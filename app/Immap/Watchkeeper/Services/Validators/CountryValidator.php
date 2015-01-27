<?php namespace Immap\Watchkeeper\Services\Validators;

class CountryValidator extends AbstractLaravelValidator {

     /**
     * Rules for a link
     * @var array
     */
    protected static $rules = array(
        'name' => 'required|unique:countries,name@id',
        'code_a3' => 'required|size:3|unique:countries,code_a3@id',
        'code_a2' => 'size:2|unique:countries,code_a2@id'
    );

}
