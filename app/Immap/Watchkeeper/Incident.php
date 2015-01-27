<?php namespace Immap\Watchkeeper;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon as Carbon;
class Incident  extends Eloquent {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'incidents';

    public function user()
    {
        return $this->belongsTo('\Immap\Watchkeeper\User','user_id');
    }

    public function country()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Country','country_id');
    }

    public function sourceGrade()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Classification','source_grade_id');
    }

    public function incidentType()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Classification','incident_type_id');
    }

    public function incidentCategory()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Classification','incident_category_id');
    }

    public function pointArea()
    {
        return $this->belongsTo('\Immap\Watchkeeper\PointArea','pointarea_id');
    }

    public function getTimeDiffAgoAttribute()
    {
        return Carbon::parse($this->attributes['incident_datetime'])->diffForHumans();
    }

    public function getDateTimeActionAttribute()
    {
        return str_replace('+0000','',Carbon::parse($this->attributes['incident_datetime'])->toISO8601String());
    }

}
