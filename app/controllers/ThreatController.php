<?php
use Immap\Watchkeeper\Repositories\Interfaces\ClassificationRepositoryInterface as ClassificationRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface as CountryRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\ThreatRepositoryInterface as ThreatRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\PointAreaRepositoryInterface as PointAreaRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
use Carbon\Carbon;
class ThreatController   extends BaseController {
    public $classificationRepo;
    public $threatRepo;
    public $countryRepo;
    public $pointareaRepo;
    private $inputSpec = array('pointArea_id','specification_location','datetime','advice',
        'title','description','source','location','injured','killed','captured','threat_type_id',
        'threat_category_id','country_id','source_grade_id','location','send_sms','send_email','geojson');
    function __construct(
        PointAreaRepositoryInterface $pointareaRepo,
        ClassificationRepositoryInterface $classificationRepo,
        CountryRepositoryInterface $countryRepo,
        ThreatRepositoryInterface $threatRepo)
    {
        $this->classificationRepo = $classificationRepo;
        $this->threatRepo = $threatRepo;
        $this->countryRepo = $countryRepo;
        $this->pointareaRepo = $pointareaRepo;

        View::composer('secm.threats.*', function($view)
        {
            $view->with('title', 'Threat Event');
        });
        View::composer('secm.threats.index', function($view)
        {
            $view->with('urlCreate', URL::route('secm.threat.create'));
        });
        View::composer(array('secm.threats.create','secm.threats.edit'), function($view)
        {
            $view->with('urlIndex', URL::route('secm.threat.index'));
            $view->with('mainpageid', 'mainpage-with-googlemap');
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showIndex($page = 1)
    {
        $page = $page <= 0 ? 1 : (int)$page;
        $me = $this;
        $max = Config::get('pagination.max');
        $callback = function($query) use($max,$page) {
            $query->with('country','sourceGrade','threatType','threatCategory','pointArea');
            $query->skip($max * ($page - 1));
            $query->take($max);
        };
        $tmp = $this->threatRepo->orderBy(array('callback' => $callback));
        $threats = JQMPaginator::make($page,$tmp->all(),$me->threatRepo->getModel()->count(),$max,'secm.threat.index.page');
        return View::make('secm.threats.index',compact('threats','paginators'));
    }

    public function getCreate()
    {
        $source_grades = $this->classificationRepo->getAllSourceGrade()->lists('name','id');
        $threat_types = $this->classificationRepo->getAllThreatType()->lists('name','id');
        $threat_categories  = $this->classificationRepo->getAllThreatCategory()->lists('name','id');
        $pointareas = $this->pointareaRepo->all()->lists('name','id');
        $now = str_replace('+0000','',Carbon::now()->toISO8601String());
        $countries = $this->countryRepo->all()->lists('name','id');
        $specification_location = "off";
        return View::make('secm.threats.create',compact('source_grades','threat_types','threat_categories','countries','pointareas','now','specification_location'));
    }

    public function getEdit($id = null)
    {
        if ($id === null) return Redirect::route('secm.threat.create');
        $callback = function($query) use($id) {
            $query->with('country','sourceGrade','threatType','threatCategory','pointarea');
            $query->where('id','=',$id);
        };
        $threat = $this->threatRepo->orderBy(array('callback' => $callback))->first();
        $source_grades = $this->classificationRepo->getAllSourceGrade()->lists('name','id');
        $threat_types = $this->classificationRepo->getAllThreatType()->lists('name','id');
        $threat_categories  = $this->classificationRepo->getAllThreatCategory()->lists('name','id');
        $pointareas = $this->pointareaRepo->all()->lists('name','id');
        $countries = $this->countryRepo->all()->lists('name','id');
        $country_id = $threat->country->id;
        $threat_type_id = $threat->threatType->id;
        $threat_category_id = $threat->threatCategory->id;
        $source_grade_id = $threat->sourceGrade->id;
        $pointarea_id = is_null($threat->pointArea) ? 1 : $threat->pointArea->id;
        $specification_location = is_null($threat->pointArea) ? "off" : "on";
        return View::make('secm.threats.edit',compact('threat','pointareas', 'source_grades','threat_types',
            'threat_categories','countries','country_id','threat_type_id','threat_category_id','source_grade_id','pointarea_id','specification_location'));
    }

    public function postStore()
    {
        try
        {
            $data = Input::only($this->inputSpec);
            $data['pointArea'] = $data['specification_location'] === 'off' ? null : $this->pointareaRepo->getModel()->where('id',$data['pointArea_id'])->first();
            $data['user'] = immap_get_user();
            $data['user_id'] = $data['user']->id;
            $data['threat_datetime'] = $data['datetime'];
            $data['threatType'] = $this->classificationRepo->getModel()->where('id',$data['threat_type_id'])->first();
            $data['threatCategory'] = $this->classificationRepo->getModel()->where('id',$data['threat_category_id'])->first();
            $data['sourceGrade'] = $this->classificationRepo->getModel()->where('id',$data['source_grade_id'])->first();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->first();
            $this->threatRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.threat.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.threat.index');
    }

    public function postUpdate($id)
    {
        try
        {
            $data = Input::only($this->inputSpec);
            $data['pointArea'] = $data['specification_location'] === 'off' ? null : $this->pointareaRepo->getModel()->where('id',$data['pointArea_id'])->first();
            $data['id'] = $id;
            $data['user'] = immap_get_user();
            $data['user_id'] = $data['user']->id;
            $data['threat_datetime'] = $data['datetime'];
            $data['threatType'] = $this->classificationRepo->getModel()->where('id',$data['threat_type_id'])->first();
            $data['threatCategory'] = $this->classificationRepo->getModel()->where('id',$data['threat_category_id'])->first();
            $data['sourceGrade'] = $this->classificationRepo->getModel()->where('id',$data['source_grade_id'])->first();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->first();
            $this->threatRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.threat.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.threat.index');
    }

    public function getDestroy($id)
    {
        $result = $this->threatRepo->getModel()->destroy($id);
        return Redirect::route('secm.threat.index');
    }
}
