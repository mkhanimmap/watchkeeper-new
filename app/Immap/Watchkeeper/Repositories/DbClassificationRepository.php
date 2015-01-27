<?php namespace Immap\Watchkeeper\Repositories;
use Immap\Watchkeeper\Classification as Classification;
use Immap\Watchkeeper\Repositories\Interfaces\ClassificationRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\CountryValidator as ClassificationValidator;
use Illuminate\Support\Facades\Event as Event;
class DbClassificationRepository extends AbstractDbRepository implements ClassificationRepositoryInterface {
    protected $defaultOrderBy = "code";
    protected $classificationTypes = array ("1" => "incident type",
                                            "2" => "incident category",
                                            "3" => "source grading",
                                            "4" => "poi type",
                                            "5" => "threat category",
                                            "6" => "threat type",
                                            "7" => "risk classification",
                                            "8" => "movement state"
                                            );
    private $classification;

    function __construct(Classification $model)
    {
        $this->model = $model;
        Event::listen('classification.saving','Immap\Watchkeeper\Services\Validators\ClassificationValidator@onSave');
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
        Event::fire('classification.saving', array($data));
        $this->classification = (isset($data['id']) && !empty($data['id'])) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->classification->code = $data['code'];
        $this->classification->name = $data['name'];
        $this->classification->group_id = $data['group_id'];
        return $this->classification->save();
    }

    public function getClassificationTypeById($id)
    {
        return $this->classificationTypes[$id];
    }

    public function getAllClassificationType()
    {
        return $this->classificationTypes;
    }

    public function getAllIncidentType()
    {
        return $this->getModel()->where('group_id','1')->get();
    }
    public function getAllIncidentCategory()
    {
        return $this->getModel()->where('group_id','2')->get();
    }
    public function getAllSourceGrade()
    {
        return $this->getModel()->where('group_id','3')->get();
    }
    public function getAllPoiType()
    {
        return $this->getModel()->where('group_id','4')->get();
    }
    public function getAllThreatCategory()
    {
        return $this->getModel()->where('group_id','5')->get();
    }
    public function getAllThreatType()
    {
        return $this->getModel()->where('group_id','6')->get();
    }
    public function getAllRiskClassification()
    {
        return $this->getModel()->where('group_id','7')->get();
    }
    public function getAllMovementState()
    {
        return $this->getModel()->where('group_id','8')->get();
    }
}

