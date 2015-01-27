<?php namespace Immap\Watchkeeper;
use Illuminate\Database\Eloquent\Model as Eloquent;
class Usergroup extends Eloquent {
    protected $fillable = array('name','code');

    public function user()
    {
        return $this->hasMany('user');
    }
}
