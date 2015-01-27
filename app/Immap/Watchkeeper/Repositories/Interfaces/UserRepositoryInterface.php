<?php namespace Immap\Watchkeeper\Repositories\Interfaces;

interface UserRepositoryInterface extends GenericRepositoryInterface {
    public function byUsername($username);
    public function byIdUsernameAndEmail($username, $username, $email);
    public function byIdWithRoles($id);
    public function getLast();
    public function hasRole($name);
    public function can($permission);
    public function ability($roles, $permissions, $options=array());
    public function attachCountries($id, $countries);
    public function attachRoles($userId, $roles);
    public function detachRoles($userId, $roles);
    public function encryptUsername($username);
    public function encryptEmail($email);
    public function decryptUsername($username);
    public function decryptEmail($email);
}
