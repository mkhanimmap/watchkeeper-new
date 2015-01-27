<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\Permission as Permission;
use Immap\Watchkeeper\Repositories\Interfaces\PermissionRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\PermissionValidator as PermissionValidator;
use Illuminate\Support\Facades\Event as Event;
class DbPermissionRepository extends AbstractDbRepository implements PermissionRepositoryInterface {

    protected $defaultOrderBy = "group_name";
    private $permission;
    function __construct(Permission $model)
    {
        $this->model = $model;
        Event::listen('permission.saving','Immap\Watchkeeper\Services\Validators\PermissionValidator@onSave');
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
        Event::fire('permission.saving', array($data));
        $this->permission = (isset($data['id']) && !empty($data['id'])) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->permission->name = $data['name'];
        $this->permission->display_name = $data['display_name'];
        $this->permission->group_name = $data['group_name'];
        $this->permission->action_name = $data['action_name'];
        return $this->permission->save();
    }

}
