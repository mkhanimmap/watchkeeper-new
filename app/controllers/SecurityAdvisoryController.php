<?php
use Immap\Watchkeeper\Repositories\Interfaces\ClassificationRepositoryInterface as ClassificationRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface as CountryRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\SecurityAdvisoryRepositoryInterface as SecurityAdvisoryRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\PointAreaRepositoryInterface as PointAreaRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
use Carbon\Carbon;
class SecurityAdvisoryController  extends BaseController {
    public $classificationRepo;
    public $securityAdvisoryRepo;
    public $countryRepo;
    public $pointareaRepo;
    private $inputSpec = array('datetime','advice','description','incidentType_id','country_id','pointArea_id',
        'location','send_sms','send_email','specification_location','geojson');
    function __construct(
        PointAreaRepositoryInterface $pointareaRepo,
        ClassificationRepositoryInterface $classificationRepo,
        CountryRepositoryInterface $countryRepo,
        SecurityAdvisoryRepositoryInterface $securityAdvisoryRepo)
    {
        $this->classificationRepo = $classificationRepo;
        $this->securityAdvisoryRepo = $securityAdvisoryRepo;
        $this->countryRepo = $countryRepo;
        $this->pointareaRepo = $pointareaRepo;

        View::composer('secm.security-advisory.*', function($view)
        {
            $view->with('title', 'Security Advisory');
        });
        View::composer('secm.security-advisory.index', function($view)
        {
            $view->with('urlCreate', URL::route('secm.security-advisory.create'));
        });
        View::composer(array('secm.security-advisory.create','secm.security-advisory.edit'), function($view)
        {
            $view->with('urlIndex', URL::route('secm.security-advisory.index'));
            $view->with('mainpageid', "mainpage-with-googlemap");
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showIndex($page = 1)
    {
        $me = $this;
        $max = Config::get('pagination.max');
        $callback = function($query) use($max,$page) {
            $query->with('country','pointArea','incidentType');
            $query->skip($max * ($page - 1));
            $query->take($max);
        };
        $tmp = $this->securityAdvisoryRepo->orderBy(array('callback' => $callback));
        $securityAdvisories = JQMPaginator::make($page,$tmp->all(),$me->securityAdvisoryRepo->getModel()->count(),$max,'secm.security-advisory.index.page');
        return View::make('secm.security-advisory.index',compact('securityAdvisories'));
    }

    public function getCreate()
    {
        $incidentTypes = $this->classificationRepo->getAllIncidentType()->lists('name','id');
        $now = str_replace('+0000','',Carbon::now()->toISO8601String());
        $pointareas = $this->pointareaRepo->all()->lists('name','id');
        $countries = $this->countryRepo->all()->lists('name','id');
        $specification_location = "off";
        return View::make('secm.security-advisory.create',compact('incidentTypes','pointareas','countries','now','specification_location'));
    }

    public function getEdit($id = null)
    {
       if ($id === null) return Redirect::route('secm.security-advisory.create');
       $callback = function($query) use($id) {
           $query->with('country','pointArea','incidentType','pointarea');
           $query->where('id','=',$id);
       };
       $securityAdvisory = $this->securityAdvisoryRepo->orderBy(array('callback' => $callback))->first();
       $incidentTypes = $this->classificationRepo->getAllIncidentType()->lists('name','id');
       $pointareas = $this->pointareaRepo->all()->lists('name','id');
       $countries = $this->countryRepo->all()->lists('name','id');
       $country_id = $securityAdvisory->country->id;
       $incidentType_id = $securityAdvisory->incidentType->id;
       $pointarea_id = is_null($securityAdvisory->pointArea) ? 1 : $securityAdvisory->pointArea->id;
       $specification_location = is_null($securityAdvisory->pointArea) ? "off" : "on";
       return View::make('secm.security-advisory.edit',compact('securityAdvisory','incidentTypes','countries',
        'pointareas','country_id','incidentType_id','pointarea_id','specification_location'));
    }

    public function postStore()
    {
        try
        {
            $data = Input::only($this->inputSpec);
            $data['pointArea'] = $data['specification_location'] === 'off' ? null : $this->pointareaRepo->getModel()->where('id',$data['pointArea_id'])->first();
            $data['user'] = immap_get_user();  //Cache::get('current_user'.Session::get('user_id'));
            $data['user_id'] =  $data['user']->id;
            $data['security_advisory_datetime'] = $data['datetime'];
            $data['incidentType'] = $this->classificationRepo->getModel()->where('id',$data['incidentType_id'])->first();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->first();
            $this->securityAdvisoryRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.security-advisory.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.security-advisory.index');
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
            $data['security_advisory_datetime'] = $data['datetime'];
            $data['incidentType'] = $this->classificationRepo->getModel()->where('id',$data['incidentType_id'])->first();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->first();
            $this->securityAdvisoryRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.security-advisory.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.security-advisory.index');
    }

    public function getDestroy($id)
    {
        $result = $this->securityAdvisoryRepo->getModel()->destroy($id);
        return Redirect::route('secm.security-advisory.index');
    }
}
