<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\SecurityAdvisory as SecurityAdvisory;
use Immap\Watchkeeper\Repositories\Interfaces\SecurityAdvisoryRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\SecurityAdvisoryValidator as SecurityAdvisoryValidator;
use Illuminate\Support\Facades\Event as Event;
class DbSecurityAdvisoryRepository extends AbstractDbRepository implements SecurityAdvisoryRepositoryInterface {

    protected $defaultOrderBy = "security_advisory_datetime";
    private $security_advisory;
    function __construct(SecurityAdvisory $model)
    {
        $this->model = $model;
        Event::listen('security_advisory.saving','Immap\Watchkeeper\Services\Validators\SecurityAdvisoryValidator@onSave');
    }

    public function create(array $data)
    {
        return $this->save($data);
    }

    public function update(array $data)
    {
        return $this->save($data);
    }

    public function save(array $data)
    {
        Event::fire('security_advisory.saving', array($data));
        $this->security_advisory = (isset($data['id']) && !empty($data['id'])) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->security_advisory->description = $data['description'];
        $this->security_advisory->advice = $data['advice'];
        $this->security_advisory->location = $data['location'];
        $this->security_advisory->send_sms = is_null($data['send_sms']) ? false : $data['send_sms'] ;
        $this->security_advisory->send_email = is_null($data['send_email']) ? false : $data['send_email'] ;
        $this->security_advisory->security_advisory_datetime = $data['datetime'];
        $this->security_advisory->user()->associate($data['user']);
        $this->security_advisory->country()->associate($data['country']);
        $this->security_advisory->incidentType()->associate($data['incidentType']);
        if ($data['pointArea'] !== null)
        {
            $this->security_advisory->pointArea()->associate($data['pointArea']);
            $this->security_advisory->geojson = '';
        }
        else
        {
            $this->security_advisory->pointarea_id = null;
            $this->security_advisory->geojson = $data['geojson'];
        }
        return $this->security_advisory->save();
    }
}
