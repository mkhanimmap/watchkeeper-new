<?php namespace Immap\Watchkeeper;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon as Carbon;
class SecurityAdvisory extends Eloquent {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'security_advisories';

    public function user()
    {
        return $this->belongsTo('\Immap\Watchkeeper\User','user_id');
    }

    public function country()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Country','country_id');
    }

    public function pointArea()
    {
        return $this->belongsTo('\Immap\Watchkeeper\PointArea','pointarea_id');
    }

    public function incidentType()
    {
        return $this->belongsTo('\Immap\Watchkeeper\Classification','incidenttype_id');
    }

    public function getDateTimeActionAttribute()
    {
        return str_replace('+0000','',Carbon::parse($this->attributes['security_advisory_datetime'])->toISO8601String());
    }

    public function getTimeDiffAgoAttribute()
    {
        return Carbon::parse($this->attributes['security_advisory_datetime'])->diffForHumans();
    }

}
