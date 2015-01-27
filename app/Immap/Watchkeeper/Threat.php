<?php namespace Immap\Watchkeeper;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon as Carbon;
class Threat  extends Eloquent {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'threats';

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

    public function threatType()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Classification','threat_type_id');
    }

    public function threatCategory()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Classification','threat_category_id');
    }

    public function pointArea()
    {
        return $this->belongsTo('\Immap\Watchkeeper\PointArea','pointarea_id');
    }

    public function getTimeDiffAgoAttribute()
    {
        return Carbon::parse($this->attributes['threat_datetime'])->diffForHumans();
    }

    public function getDateTimeActionAttribute()
    {
        return str_replace('+0000','',Carbon::parse($this->attributes['threat_datetime'])->toISO8601String());
    }

}
