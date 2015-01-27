<?php namespace Immap\Watchkeeper\Repositories\Interfaces;

interface RoleRepositoryInterface extends GenericRepositoryInterface {
    public function getLast();
    public function savePermissions($id ,array $permssions);
    public function attachPermission($id, $permission);
    public function byIdWithPermissions($id);
}
