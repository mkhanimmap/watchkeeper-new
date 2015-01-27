<?php namespace Immap\Watchkeeper\Repositories\Interfaces;

interface UsergroupRepositoryInterface extends GenericRepositoryInterface {
    public function allWithUser();
    public function byUserId($userid);
    public function byIdWithUser($id);
}
