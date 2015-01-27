<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\PointArea as PointArea;
use Immap\Watchkeeper\Repositories\Interfaces\PointAreaRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\PointAreaValidator as PointAreaValidator;
use Illuminate\Support\Facades\Event as Event;
class DbPointAreaRepository extends AbstractDbRepository implements PointAreaRepositoryInterface {

    protected $defaultOrderBy = "name";
    private $pointarea;
    function __construct(PointArea $model)
    {
        $this->model = $model;
        Event::listen('pointarea.saving','Immap\Watchkeeper\Services\Validators\PointAreaValidator@onSave');
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
        Event::fire('pointarea.saving', array($data));
        $this->pointarea = ( isset($data['id']) && !empty($data['id']) ) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->pointarea->name = $data['name'];
        $this->pointarea->description = $data['description'];
        $this->pointarea->geojson = $data['geojson'];
        return $this->pointarea->save();
    }
}
