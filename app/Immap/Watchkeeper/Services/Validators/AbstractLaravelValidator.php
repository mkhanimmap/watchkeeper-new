<?php namespace Immap\Watchkeeper\Services\Validators;;

use Illuminate\Validation\Factory as Factory;
abstract class AbstractLaravelValidator implements ValidatorInterface {

    /**
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Trigger validation
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function onCreate(array $data)
    {
        return $this->onSave($data);
    }

    /**
     * Trigger validation
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function onUpdate(array $data)
    {
       return $this->onSave($data);
    }

    /**
     * Trigger validation
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function onSave(array $data)
    {
        if (isset($data['id']))
        {
            $rules = str_replace('@id', ','.$data['id'], static::$rules);
        }
        else
        {
            $rules = str_replace("@id",'', static::$rules);
        }
        $validation = $this->validator->make( $data, $rules);
        if ($validation->fails()) throw new ValidationException($validation->messages());
        return true;
    }
}
