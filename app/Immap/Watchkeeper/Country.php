<?php namespace Immap\Watchkeeper;
use Illuminate\Database\Eloquent\Model as Eloquent;
class Country extends Eloquent {
    protected $fillable = array('name','code_a2','code_a3','active');

    public function users()
    {
        return $this->belongsToMany('\Immap\Watchkeeper\User', 'assigned_countries')->withTimestamps();;
    }

    public function getStatusAttribute()
    {
        return $this->attributes['active'] == TRUE ? 'active' : 'inactive';
    }

    public function getConvertStatusAttribute()
    {
        return $this->attributes['active'] == TRUE ? 'inactive' : 'active';
    }
}

