<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\Incident as Incident;
use Immap\Watchkeeper\Repositories\Interfaces\IncidentRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\IncidentValidator as IncidentValidator;
use Illuminate\Support\Facades\Event as Event;
class DbIncidentRepository extends AbstractDbRepository implements IncidentRepositoryInterface {

    protected $defaultOrderBy = "incident_datetime";
    private $incident;
    function __construct(Incident $model)
    {
        $this->model = $model;
        Event::listen('incident.saving','Immap\Watchkeeper\Services\Validators\IncidentValidator@onSave');
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
        Event::fire('incident.saving', array($data));
        $this->incident = ( isset($data['id']) && !empty($data['id']) ) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->incident->incident_datetime = $data['incident_datetime'];
        $this->incident->description = $data['description'];
        $this->incident->source = $data['source'];
        $this->incident->location = $data['location'];
        $this->incident->injured = (int)$data['injured'];
        $this->incident->killed = (int)$data['killed'];
        $this->incident->captured = (int)$data['captured'];
        $this->incident->send_sms = is_null($data['send_sms']) ? false : $data['send_sms'] ;
        $this->incident->send_email = is_null($data['send_email']) ? false : $data['send_email'] ;
        $this->incident->geojson = $data['geojson'];
        $this->incident->user()->associate($data['user']);
        $this->incident->country()->associate($data['country']);
        $this->incident->sourceGrade()->associate($data['sourceGrade']);
        $this->incident->incidentType()->associate($data['incidentType']);
        $this->incident->incidentCategory()->associate($data['incidentCategory']);
        if ($data['pointArea'] !== null)
        {
            $this->incident->pointArea()->associate($data['pointArea']);
            $this->incident->geojson = '';
        }
        else
        {
            $this->incident->pointarea_id = null;
            $this->incident->geojson = $data['geojson'];
        }
        return $this->incident->save();
    }
}
