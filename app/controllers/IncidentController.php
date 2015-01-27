<?php
use Immap\Watchkeeper\Repositories\Interfaces\ClassificationRepositoryInterface as ClassificationRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface as CountryRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\IncidentRepositoryInterface as IncidentRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\PointAreaRepositoryInterface as PointAreaRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
use Carbon\Carbon;
class IncidentController extends BaseController {

    public $classificationRepo;
    public $incientRepo;
    public $countryRepo;
    public $pointareaRepo;
    private $inputSpec = array('pointArea_id','specification_location','datetime',
        'description','source','location','injured','killed','captured','incident_type_id',
        'incident_category_id','country_id','source_grade_id','location','send_sms','send_email','geojson');
    function __construct(
        PointAreaRepositoryInterface $pointareaRepo,
        ClassificationRepositoryInterface $classificationRepo,
        CountryRepositoryInterface $countryRepo,
        IncidentRepositoryInterface $incientRepo)
    {
        $this->classificationRepo = $classificationRepo;
        $this->incientRepo = $incientRepo;
        $this->countryRepo = $countryRepo;
        $this->pointareaRepo = $pointareaRepo;

        View::composer('secm.ina.*', function($view)
        {
            $view->with('title', 'Incident Alert');
        });
        View::composer('secm.ina.index', function($view)
        {
            $view->with('urlCreate', URL::route('secm.ina.create'));
        });
        View::composer(array('secm.ina.create','secm.ina.edit'), function($view)
        {
            $view->with('urlIndex', URL::route('secm.ina.index'));
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
            $query->with('country','sourceGrade','incidentType','incidentCategory','pointArea');
            $query->skip($max * ($page - 1));
            $query->take($max);
        };
        $tmp = $this->incientRepo->orderBy(array('callback' => $callback));
        $incidents = JQMPaginator::make($page,$tmp->all(),$me->incientRepo->getModel()->count(),$max,'secm.ina.index.page');
        return View::make('secm.ina.index',compact('incidents','paginators'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        $source_grades = $this->classificationRepo->getAllSourceGrade()->lists('name','id');
        $incident_types = $this->classificationRepo->getAllIncidentType()->lists('name','id');
        $incident_categories  = $this->classificationRepo->getAllIncidentCategory()->lists('name','id');
        $pointareas = $this->pointareaRepo->all()->lists('name','id');
        $now = str_replace('+0000','',Carbon::now()->toISO8601String());
        $countries = $this->countryRepo->all()->lists('name','id');
        $specification_location = "off";
        return View::make('secm.ina.create',compact('source_grades','incident_types','incident_categories','countries','pointareas','now','specification_location'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postStore()
    {

        try
        {
            $data = Input::only($this->inputSpec);
            $data['pointArea'] = $data['specification_location'] === 'off' ? null : $this->pointareaRepo->getModel()->where('id',$data['pointArea_id'])->first();
            $data['user'] = immap_get_user();
            $data['user_id'] = $data['user']->id;
            $data['incident_datetime'] = $data['datetime'];
            $data['incidentType'] = $this->classificationRepo->getModel()->where('id',$data['incident_type_id'])->first();
            $data['incidentCategory'] = $this->classificationRepo->getModel()->where('id',$data['incident_category_id'])->first();
            $data['sourceGrade'] = $this->classificationRepo->getModel()->where('id',$data['source_grade_id'])->first();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->first();
            $this->incientRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.ina.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.ina.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id)
    {
        if ($id === null) return Redirect::route('secm.ina.create');
        $callback = function($query) use($id) {
            $query->with('country','sourceGrade','incidentType','incidentCategory','pointarea');
            $query->where('id','=',$id);
        };
        $incident = $this->incientRepo->orderBy(array('callback' => $callback))->first();
        $source_grades = $this->classificationRepo->getAllSourceGrade()->lists('name','id');
        $incident_types = $this->classificationRepo->getAllIncidentType()->lists('name','id');
        $incident_categories  = $this->classificationRepo->getAllIncidentCategory()->lists('name','id');
        $pointareas = $this->pointareaRepo->all()->lists('name','id');
        $countries = $this->countryRepo->all()->lists('name','id');
        $country_id = $incident->country->id;
        $incident_type_id = $incident->incidentType->id;
        $incident_category_id = $incident->incidentCategory->id;
        $source_grade_id = $incident->sourceGrade->id;
        $pointarea_id = is_null($incident->pointArea) ? 1 : $incident->pointArea->id;
        $specification_location = is_null($incident->pointArea) ? "off" : "on";
        return View::make('secm.ina.edit',compact('incident','pointareas', 'source_grades','incident_types',
         'incident_categories','countries','country_id','incident_type_id','incident_category_id','source_grade_id','pointarea_id','specification_location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function postUpdate($id)
    {
        try
        {
            $data = Input::only($this->inputSpec);
            $data['pointArea'] = $data['specification_location'] === 'off' ? null : $this->pointareaRepo->getModel()->where('id',$data['pointArea_id'])->first();
            $data['id'] = $id;
            $data['user'] = immap_get_user();
            $data['user_id'] = $data['user']->id;
            $data['incident_datetime'] = $data['datetime'];
            $data['incidentType'] = $this->classificationRepo->getModel()->where('id',$data['incident_type_id'])->first();
            $data['incidentCategory'] = $this->classificationRepo->getModel()->where('id',$data['incident_category_id'])->first();
            $data['sourceGrade'] = $this->classificationRepo->getModel()->where('id',$data['source_grade_id'])->first();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->first();
            $this->incientRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.ina.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.ina.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $result = $this->incientRepo->getModel()->destroy($id);
        return Redirect::route('secm.ina.index');
    }

}
