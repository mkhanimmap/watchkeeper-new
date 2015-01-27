<?php namespace Immap\Watchkeeper\Services\Validators;

class ValidationException extends \Exception {

    /**
    * @var string
    */
    protected $errors;

   /**
    * @param string
    */
    function __construct($errors)
    {
        $this->errors = $errors;
    }
    /**
     * Fetch validation errors
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }
}

