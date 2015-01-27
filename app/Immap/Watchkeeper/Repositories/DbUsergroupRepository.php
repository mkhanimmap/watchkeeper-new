<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\Usergroup as Usergroup;
use Immap\Watchkeeper\Repositories\Interfaces\UsergroupRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\UsergroupValidator as UsergroupValidator;
use Illuminate\Support\Facades\Event as Event;
class DbUsergroupRepository  extends AbstractDbRepository implements UsergroupRepositoryInterface {

    protected $defaultOrderBy = "name";

    function __construct(Usergroup $model)
    {
        $this->model = $model;
        Event::listen('usergroup.saving','Immap\Watchkeeper\Services\Validators\UsergroupValidator@onSave');
    }

    public  function byUserId($userid)
    {
        return $this->model->user->findOrFail($userid);
    }

    public function allWithUser()
    {
        return $this->model->with('user')->get();
    }

    public  function byIdWithUser($id)
    {
        return $this->model->with('user')->findOrFail($id);
    }

    public function save(array $data)
    {
        Event::fire('usergroup.saving', array($data));
        return parent::save($data);
    }
}
