<?php namespace Immap\Watchkeeper\Services\Validators;

use Illuminate\Validation\Factory as Factory;
class UserValidator extends AbstractLaravelValidator {

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }
     /**
     * Rules for a link
     * @var array
     */
     protected static $rules = array(
         'username' => 'required|between:3,20|unique:users,username@id',
         'email' => 'required|email|unique:users,email@id',
         'firstname' => 'required',
     );
}
