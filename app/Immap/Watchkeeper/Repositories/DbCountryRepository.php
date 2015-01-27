<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\Country as Country;
use Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\CountryValidator as CountryValidator;
use Illuminate\Support\Facades\Event as Event;
class DbCountryRepository extends AbstractDbRepository implements CountryRepositoryInterface {

    protected $defaultOrderBy = "name";

    function __construct(Country $model)
    {
        $this->model = $model;
        Event::listen('country.saving','Immap\Watchkeeper\Services\Validators\CountryValidator@onSave');
    }

    function getCountry($includeInactive = false)
    {
        $model = $includeInactive == true ? $this->getModel() : $this->getModel()->where('active',true);
        return $model->orderBy('name','asc')->get();
    }

    public function save(array $data)
    {
        Event::fire('country.saving', array($data));
        return parent::save($data);
    }

    public function changeStatus($id, $status='inactive')
    {
        $country = $this->model->where('id','=',$id)->firstOrFail();
        $country->active = $status=='active' ? true : false;
        return $country->save();
    }
}
