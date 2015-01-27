<?php namespace Immap\Watchkeeper;
use Illuminate\Database\Eloquent\Model as Eloquent;
class PointArea extends Eloquent {
    protected $fillable = array('name','description','wkt','geojson');
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pointareas';
}
