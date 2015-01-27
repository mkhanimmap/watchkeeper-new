<?php namespace Immap\Watchkeeper\Repositories;
use Closure;
use Callbacks;
use Immap\Watchkeeper\Repositories\Interfaces as RepoInterface;
use Illuminate\Config\Repository as Config;
use Illuminate\Support\SerializableClosure;
class AbstractDbRepository implements RepoInterface\GenericRepositoryInterface
{
    protected $model;
    protected $fullModelName;
    protected $defaultOrderBy ='id';

    public function all()
    {
        return $this->model->all();
    }
    public function byId($id)
    {
        return  $this->model->where('id','=',$id)->firstOrFail();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
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
        if( isset($data['id']) || ! empty($data['id']) )
        {
            $model = $this->model->findOrFail($data['id'])->update($data);
            return $model;
        }
        else
        {
            return call_user_func(get_class($this->model)."::Create",$data);
        }

    }

    public function getPaginated(array $params)
    {
        $max = empty($params['max']) ?  \Config::get('pagination.max') : $params['max'];
        if ($this->isSortable($params))
        {
            $query = $this->model->orderBy($params['sort'], $params['direction']);
        }
        else
        {
            $query = $this->model->orderBy($this->defaultOrderBy);
        }
        if ($this->isCallback($params))
        {
            $this->callQueryBuilder($params['callback'], $query);
        }
        return $query->paginate($max);
    }

    protected function isSortable(array $params)
    {
        return isset($params['sort']) && $params['sort'] && isset($params['direction']) && $params['direction'];
    }

    protected function isCallback(array $params)
    {
        return isset($params['callback']) && $params['callback'];
    }


    public function orderBy(array $params)
    {
        extract($params);
        $sort = isset($sort) ? $sort : $this->defaultOrderBy;
        $direction = isset($direction) ? $direction : 'DESC';
        $callback = isset($callback) ? $callback : null;
        $query = $this->model->orderBy($sort, $direction);
        if ($callback != null) $this->callQueryBuilder($callback, $query);
        return $query->get();
    }
    protected function callQueryBuilder($callback, $query)
    {
        if ($callback instanceof Closure)
        {
            return call_user_func($callback, $query);
        }
        throw new \InvalidArgumentException("Callback is not valid.");
    }

    public function deleteById($id)
    {
        $this->model = $this->byId($id);
        return $this->model->delete();
    }
}
