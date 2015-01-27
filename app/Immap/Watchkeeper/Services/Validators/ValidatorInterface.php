<?php namespace Immap\Watchkeeper\Services\Validators;

interface ValidatorInterface {

    /**
     * Trigger validation
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function onCreate(array $data);

    /**
     * Trigger validation
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function onUpdate(array $data);

    /**
     * Trigger validation
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function onSave(array $data);
}
