<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\Poi as Poi;
use Immap\Watchkeeper\Repositories\Interfaces\PoiRepositoryInterface as PoiRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\PoiValidator as PoiValidator;
use Illuminate\Support\Facades\Event as Event;
class DbPoiRepository extends AbstractDbRepository implements PoiRepositoryInterface {

    protected $defaultOrderBy = "poi_datetime";
    private $poi;
    function __construct(Poi $model)
    {
        $this->model = $model;
        Event::listen('poi.saving','Immap\Watchkeeper\Services\Validators\PoiValidator@onSave');
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
        Event::fire('poi.saving', array($data));
        $this->poi = (isset($data['id']) && !empty($data['id'])) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->poi->description = $data['description'];
        $this->poi->location = $data['location'];
        $this->poi->sent_alert = ($data['alert'] == 'no') ? false : true ;
        $this->poi->immap_asset = ($data['immap_asset'] == 'no') ? false : true ;
        $distance_km = (float)$data['distance_km'];
        if ($this->poi->immap_asset === true && $distance_km <= 0)
        {
            $this->poi->distance_km = 20;
        }
        elseif ($this->poi->immap_asset === true)
        {
            $this->poi->distance_km = $distance_km;
        }
        else
        {
            $this->poi->distance_km = 0;
        }
        $this->poi->poi_datetime = $data['poi_datetime'];
        $this->poi->user()->associate($data['user']);
        $this->poi->country()->associate($data['country']);
        $this->poi->poiType()->associate($data['poiType']);
        if ($data['pointArea'] !== null)
        {
            $this->poi->pointArea()->associate($data['pointArea']);
            $this->poi->geojson = '';
        }
        else
        {
            $this->poi->pointarea_id = null;
            $this->poi->geojson = $data['geojson'];
        }
        if ( $data['image'] !== null ) {

            if (!is_null($this->poi->file_path) && !empty($this->poi->file_path) && \File::exists($this->poi->file_path))
            {
                \File::delete($this->poi->file_path);
            }
            $file = $data['image'];
            $destinationPath = \Config::get('upload.poi_image_upload_path');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $uploadSuccess = $file->move($destinationPath, $filename);
            $this->poi->file_path =  $destinationPath .'/'. $filename;
        }
        return $this->poi->save();
    }
}
