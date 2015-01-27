<?php namespace Immap\Watchkeeper;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon as Carbon;
class Risk extends Eloquent {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'risk_movement';

    public function user()
    {
        return $this->belongsTo('\Immap\Watchkeeper\User','user_id');
    }

    public function country()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Country','country_id');
    }

    public function risklevel()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Classification','risklevel_id');
    }

    public function movement()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Classification','movement_id');
    }

    public function pointArea()
    {
        return $this->belongsTo('\Immap\Watchkeeper\PointArea','pointarea_id');
    }


    public function getTimeDiffAgoAttribute()
    {
        return Carbon::parse($this->attributes['risk_datetime'])->diffForHumans();
    }

    public function getDateTimeActionAttribute()
    {
        return str_replace('+0000','',Carbon::parse($this->attributes['risk_datetime'])->toISO8601String());
    }
}
