<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\Risk as Risk;
use Immap\Watchkeeper\Repositories\Interfaces\RiskRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\RiskValidator as RiskValidator;
use Illuminate\Support\Facades\Event as Event;
class DbRiskRepository extends AbstractDbRepository implements RiskRepositoryInterface {

    protected $defaultOrderBy = "risk_datetime";
    protected $risk;
    function __construct(Risk $model)
    {
        $this->model = $model;
        Event::listen('risk.saving','Immap\Watchkeeper\Services\Validators\RiskValidator@onSave');
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
        Event::fire('risk.saving', array($data));
        $this->risk = (isset($data['id']) && !empty($data['id'])) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->risk->description = $data['description'];
        $this->risk->location = $data['location'];
        $this->risk->risk_datetime = $data['risk_datetime'];
        $this->risk->user()->associate($data['user']);
        $this->risk->country()->associate($data['country']);
        $this->risk->risklevel()->associate($data['risklevel']);
        $this->risk->movement()->associate($data['movement']);
        if ($data['pointArea'] !== null)
        {
            $this->risk->pointArea()->associate($data['pointArea']);
            $this->risk->geojson = '';
        }
        else
        {
            $this->risk->pointarea_id = null;
            $this->risk->geojson = $data['geojson'];
        }
        return $this->risk->save();
    }
}
