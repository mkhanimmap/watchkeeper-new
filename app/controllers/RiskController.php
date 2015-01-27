<?php
use Immap\Watchkeeper\Repositories\Interfaces\ClassificationRepositoryInterface as ClassificationRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface as CountryRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\RiskRepositoryInterface as RiskRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\PointAreaRepositoryInterface as PointAreaRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
use Carbon\Carbon;
class RiskController  extends BaseController {
    public $classificationRepo;
    public $riskRepo;
    public $countryRepo;
    public $inputSpec = array('pointArea_id','specification_location','geojson','datetime','description','risklevel_id','country_id','movement_id','location');
    function __construct(
        PointAreaRepositoryInterface $pointareaRepo,
        ClassificationRepositoryInterface $classificationRepo,
        CountryRepositoryInterface $countryRepo,
        RiskRepositoryInterface $riskRepo)
    {
        $this->classificationRepo = $classificationRepo;
        $this->riskRepo = $riskRepo;
        $this->countryRepo = $countryRepo;
        $this->pointareaRepo = $pointareaRepo;
        View::composer('secm.risks.*', function($view)
        {
            $view->with('title', 'Risk Level and Movement State');
        });
        View::composer('secm.risks.index', function($view)
        {
            $view->with('urlCreate', URL::route('secm.risk.create'));
        });
        View::composer(array('secm.risks.create','secm.risks.edit'), function($view)
        {
            $view->with('urlIndex', URL::route('secm.risk.index'));
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
            $query->with('country','risklevel','movement');
            $query->skip($max * ($page - 1));
            $query->take($max);
        };
        $tmp = $this->riskRepo->orderBy(array('callback' => $callback));
        $risks = JQMPaginator::make($page,$tmp->all(),$me->riskRepo->getModel()->count(),$max,'secm.risk.index.page');
        return View::make('secm.risks.index',compact('risks'));
    }

    public function getCreate()
    {
        $risklevels = $this->classificationRepo->getAllRiskClassification()->lists('name','id');
        $movements = $this->classificationRepo->getAllMovementState()->lists('name','id');
        $now = str_replace('+0000','',Carbon::now()->toISO8601String());
        $pointareas = $this->pointareaRepo->all()->lists('name','id');
        $countries = $this->countryRepo->all()->lists('name','id');
        $specification_location = "off";
        return View::make('secm.risks.create',compact('risklevels','movements','countries','now','specification_location','pointareas'));
    }

    public function getEdit($id = null)
    {
        if ($id === null) return Redirect::route('secm.risk.create');
        $callback = function($query) use($id) {
            $query->with('country','risklevel','movement','pointarea');
            $query->where('id','=',$id);
        };
        $risk = $this->riskRepo->orderBy(array('callback' => $callback))->first();
        $risklevels = $this->classificationRepo->getAllRiskClassification()->lists('name','id');
        $movements = $this->classificationRepo->getAllMovementState()->lists('name','id');
        $pointareas = $this->pointareaRepo->all()->lists('name','id');
        $countries = $this->countryRepo->all()->lists('name','id');
        $country_id = $risk->country->id;
        $risklevel_id = $risk->risklevel->id;
        $pointarea_id = is_null($risk->pointArea) ? 1 : $risk->pointArea->id;
        $movement_id = $risk->movement->id;
        $specification_location = is_null($risk->pointArea) ? "off" : "on";
        return View::make('secm.risks.edit',compact('risk','risklevels','movements','countries','pointareas','country_id','risklevel_id','movement_id','pointarea_id','specification_location'));
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
            $data['risk_datetime'] = $data['datetime'];
            $data['risklevel'] = $this->classificationRepo->getModel()->where('id',$data['risklevel_id'])->first();
            $data['movement'] = $this->classificationRepo->getModel()->where('id',$data['movement_id'])->first();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->first();
            $this->riskRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.risk.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.risk.index');
    }

    public function postStore()
    {
        try
        {
            $data = Input::only($this->inputSpec);
            $data['pointArea'] = $data['specification_location'] === 'off' ? null : $this->pointareaRepo->getModel()->where('id',$data['pointArea_id'])->first();
            $data['user'] = immap_get_user();
            $data['user_id'] = $data['user']->id;
            $data['risk_datetime'] = $data['datetime'];
            $data['risklevel'] = $this->classificationRepo->getModel()->where('id',$data['risklevel_id'])->first();
            $data['movement'] = $this->classificationRepo->getModel()->where('id',$data['movement_id'])->first();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->first();
            $this->riskRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.risk.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.risk.index');
    }

    public function getDestroy($id)
    {
        $result = $this->riskRepo->getModel()->destroy($id);
        return Redirect::route('secm.risk.index');
    }
}
