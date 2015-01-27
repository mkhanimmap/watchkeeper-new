<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\Threat as Threat;
use Immap\Watchkeeper\Repositories\Interfaces\ThreatRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\ThreatValidator as ThreatValidator;
use Illuminate\Support\Facades\Event as Event;
class DbThreatRepository extends AbstractDbRepository implements ThreatRepositoryInterface {

    protected $defaultOrderBy = "threat_datetime";
    private $threat;
    function __construct(Threat $model)
    {
        $this->model = $model;
        Event::listen('threat.saving','Immap\Watchkeeper\Services\Validators\ThreatValidator@onSave');
        Event::listen('threat.send.email.threat','Immap\Watchkeeper\Services\EmailSenders\AlertEmailSender@sendThreat');
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
        Event::fire('threat.saving', array($data));
        $this->threat = ( isset($data['id']) && !empty($data['id']) ) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->threat->threat_datetime = $data['threat_datetime'];
        $this->threat->description = $data['description'];
        $this->threat->title = $data['title'];
        $this->threat->source = $data['source'];
        $this->threat->advice = $data['advice'];
        $this->threat->location = $data['location'];
        $this->threat->injured = (int)$data['injured'];
        $this->threat->killed = (int)$data['killed'];
        $this->threat->captured = (int)$data['captured'];
        $this->threat->send_sms = is_null($data['send_sms']) ? false : $data['send_sms'] ;
        $this->threat->send_email = is_null($data['send_email']) ? false : $data['send_email'] ;
        $this->threat->user()->associate($data['user']);
        $this->threat->country()->associate($data['country']);
        $this->threat->sourceGrade()->associate($data['sourceGrade']);
        $this->threat->threatType()->associate($data['threatType']);
        $this->threat->threatCategory()->associate($data['threatCategory']);
        if ($data['pointArea'] !== null)
        {
            $this->threat->pointArea()->associate($data['pointArea']);
            $this->threat->geojson = '';
        }
        else
        {
            $this->threat->pointarea_id = null;
            $this->threat->geojson = $data['geojson'];
        }



        if (!isset($data['id']) && empty($data['id']))
        {

            $users = \Immap\Watchkeeper\User::join('assigned_countries','users.id','=','assigned_countries.user_id')
                ->join('countries', function($join) {
                    $join->on('assigned_countries.country_id','=','countries.id')
                         ->where('countries.active', '=', true);
                })->where('countries.id','=',$data['country']->id)->get();
            foreach ($users as $user)
            {
                Event::fire('threat.send.email.threat',array('user' => $user,'threat' => $this->threat));
            }
        }
        return $this->threat->save();;
    }
}
