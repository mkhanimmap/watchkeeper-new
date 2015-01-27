<?php namespace Immap\Watchkeeper\Repositories;

use Immap\Watchkeeper\Role as Role;
use Immap\Watchkeeper\Permission as Permission;
use Immap\Watchkeeper\Repositories\Interfaces\RoleRepositoryInterface;
use Immap\Watchkeeper\Services\Validators;
use Immap\Watchkeeper\Services\Validators\RoleValidator as RoleValidator;
use Illuminate\Support\Facades\Event as Event;
class DbRoleRepository extends AbstractDbRepository implements RoleRepositoryInterface {

    protected $defaultOrderBy = "display_name";
    private $role;

    function __construct(Role $model)
    {
        $this->model = $model;
        Event::listen('role.saving','Immap\Watchkeeper\Services\Validators\RoleValidator@onSave');
    }

    function getLast()
    {
        return $this->model->orderby('id','desc')->firstOrFail();
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
        Event::fire('role.saving', array($data));
        $this->role = (isset($data['id']) && !empty($data['id'])) ? $this->model->where('id','=',$data['id'])->firstOrFail() : new $this->model();
        $this->role->name = $data['name'];
        $this->role->display_name = $data['display_name'];
        return $this->role->save();
    }

    public function savePermissions($id,array $permssions)
    {
        $role = $this->model->where('id','=',$id)
                            ->firstOrFail();
        $role->savePermissions($permssions);
    }
    public function attachPermission($id, $permission)
    {
        $role = $this->model->where('id','=',$id)
                            ->firstOrFail();
        $role->attachPermission($permission);
    }

    public function byIdWithPermissions($id)
    {
        return $this->model->with('perms')->where('id',$id)->firstOrFail();
    }
}
