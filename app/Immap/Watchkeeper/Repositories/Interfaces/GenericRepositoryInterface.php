<?php namespace Immap\Watchkeeper\Repositories\Interfaces;

interface GenericRepositoryInterface {
    public function all();
    public function byId($id);
    public function getModel();
    public function setModel($model);
    /**
     * Argument as array of model
     * @param array @params
     */
    public function create(array $data);
    /**
     * Argument as array of model
     * @param array @params
     */
    public function update(array $data);
    /**
     * Argument as array of model
     * @param array @params
     */
    public function save(array $data);
    /**
     * Argument as array
     * @param array @params
     * @param string $direction
     * @param string $sort
     * @param max $direction
     * @param Closure $callback($query)
     *
     * @return Laravel Eloquent Collection
     * @throws InvalidArgumentException if a callback is not Closure  missing
     */
    public function getPaginated(array $params);
    /**
     * Order by
     * @param array @params
     * @param string $ysort
     * @param string $direction
     * @param Closure $callback($query)
     *
     * @return Laravel Eloquent Collection
     * @throws InvalidArgumentException if a parameter is missing
     */
    public function orderBy(array $params);
}
