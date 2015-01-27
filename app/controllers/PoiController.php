<?php
use Immap\Watchkeeper\Repositories\Interfaces\ClassificationRepositoryInterface as ClassificationRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\CountryRepositoryInterface as CountryRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\PoiRepositoryInterface as PoiRepositoryInterface;
use Immap\Watchkeeper\Repositories\Interfaces\PointAreaRepositoryInterface as PointAreaRepositoryInterface;
use Immap\Watchkeeper\Services\Validators\ValidationException as ValidationException;
use Carbon\Carbon;
class PoiController  extends BaseController {
    public $classificationRepo;
    public $poiRepo;
    public $countryRepo;
    public $pointareaRepo;
    private $inputSpec = array('datetime','description','poiType_id','country_id','pointArea_id',
        'location','send_sms','send_email','specification_location','geojson','distance_km','immap_asset','alert');
    function __construct(
        PointAreaRepositoryInterface $pointareaRepo,
        ClassificationRepositoryInterface $classificationRepo,
        CountryRepositoryInterface $countryRepo,
        PoiRepositoryInterface $poiRepo)
    {
        $this->classificationRepo = $classificationRepo;
        $this->poiRepo = $poiRepo;
        $this->countryRepo = $countryRepo;
        $this->pointareaRepo = $pointareaRepo;

        View::composer('secm.poi.*', function($view)
        {
            $view->with('title', 'Point of Incident');
        });
        View::composer('secm.poi.index', function($view)
        {
            $view->with('urlCreate', URL::route('secm.poi.create'));
        });
        View::composer(array('secm.poi.create','secm.poi.edit'), function($view)
        {
            $view->with('urlIndex', URL::route('secm.poi.index'));
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
            $query->with('country','pointArea','poiType');
            $query->skip($max * ($page - 1));
            $query->take($max);
        };
        $tmp = $this->poiRepo->orderBy(array('callback' => $callback));
        $pois = JQMPaginator::make($page,$tmp->all(),$me->poiRepo->getModel()->count(),$max,'secm.poi.index.page');
        return View::make('secm.poi.index',compact('pois'));
    }

    public function getCreate()
    {
        $poiTypes = $this->classificationRepo->getAllPoiType()->lists('name','id');
        $now = str_replace('+0000','',Carbon::now()->toISO8601String());
        $pointareas = $this->pointareaRepo->all()->lists('name','id');
        $countries = $this->countryRepo->all()->lists('name','id');
        $specification_location = "off";
        $alert = "no";
        $immap_asset = "yes";
        $img_path = URL::asset('poi_img/noimg.png');
        return View::make('secm.poi.create',compact('poiTypes','pointareas','countries','now',
            'specification_location','alert','immap_asset','img_path'));
    }

    public function getEdit($id = null)
    {
       if ($id === null) return Redirect::route('secm.poi.create');
       $callback = function($query) use($id) {
           $query->with('country','pointArea','poiType','pointarea');
           $query->where('id','=',$id);
       };
       $poi = $this->poiRepo->orderBy(array('callback' => $callback))->first();
       $poiTypes = $this->classificationRepo->getAllPoiType()->lists('name','id');
       $pointareas = $this->pointareaRepo->all()->lists('name','id');
       $countries = $this->countryRepo->all()->lists('name','id');
       $country_id = $poi->country->id;
       $poiType_id = $poi->poiType->id;
       $img_path =  (!is_null($poi->file_path) && !empty($poi->file_path) && \File::exists($poi->file_path)) ? (URL::asset(str_replace(public_path(), '', $poi->file_path))) : URL::asset('poi_img/noimg.png');
       $pointarea_id = is_null($poi->pointArea) ? 1 : $poi->pointArea->id;
       $specification_location = is_null($poi->pointArea) ? "off" : "on";
       $alert = ($poi->sent_alert == true) ? "yes" : "no";
       $immap_asset = ($poi->immap_asset == true) ? "yes" : "no";
       return View::make('secm.poi.edit',compact('poi','poiTypes','countries',
        'pointareas','country_id','poiType_id','pointarea_id',
        'specification_location','alert','immap_asset','img_path'));
    }

    public function postStore()
    {
        try
        {
            $data = Input::only($this->inputSpec);
            $data['pointArea'] = $data['specification_location'] === 'off' ? null : $this->pointareaRepo->getModel()->where('id',$data['pointArea_id'])->firstOrFail();
            $data['user'] = immap_get_user();
            $data['user_id'] = $data['user']->id;
            $data['poi_datetime'] = $data['datetime'];
            $data['poiType'] = $this->classificationRepo->getModel()->where('id',$data['poiType_id'])->firstOrFail();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->firstOrFail();
            $data['image'] = Input::hasFile('image') ? Input::file('image') : null;
            $this->poiRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.poi.create')->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.poi.index');
    }

    public function postUpdate($id)
    {
        try
        {
            $data = Input::only($this->inputSpec);
            $data['pointArea'] = $data['specification_location'] === 'off' ? null : $this->pointareaRepo->getModel()->where('id',$data['pointArea_id'])->firstOrFail();
            $data['id'] = $id;
            $data['user'] = immap_get_user();
            $data['user_id'] = $data['user']->id;
            $data['poi_datetime'] = $data['datetime'];
            $data['poiType'] = $this->classificationRepo->getModel()->where('id',$data['poiType_id'])->firstOrFail();
            $data['country'] = $this->countryRepo->getModel()->where('id',$data['country_id'])->firstOrFail();
            $data['image'] = Input::hasFile('image') ? Input::file('image') : null;
            $this->poiRepo->save($data);
        }
        catch (ValidationException $e)
        {
            return Redirect::route('secm.poi.edit',$id)->withErrors($e->getErrors())->withInput();
        }
        return Redirect::route('secm.poi.index');
    }

    public function getDestroy($id)
    {
        $result = $this->poiRepo->getModel()->destroy($id);
        return Redirect::route('secm.poi.index');
    }
}
