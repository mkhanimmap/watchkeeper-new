<?php namespace Immap\Watchkeeper;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Immap\Watchkeeper\Repositories\DbClassificationRepository;
class Classification extends Eloquent {
    //protected $table = 'classifications';
	protected $guarded = array();

	public static $rules = array();

}
