<?php

function sort_by($column, $body, $route)
{
    $queryString = Request::except(array('sort','direction','page'));
    if (Request::get('sort') !== $column)
    {
        $direction = (Request::get('direction') == 'asc') ? 'asc' : 'desc';
    }
    else
    {
        $direction = (Request::get('direction') == 'asc') ? 'desc' : 'asc';
    }
    $queryString['sort'] = $column;
    $queryString['direction'] = $direction;
    return link_to_route($route, $body, $queryString);
}


function pagination_links($klass)
{
    $queryString = Request::except(array('sort','direction','page'));
    $direction = (Request::get('direction') == 'desc') ? 'desc' : 'asc';
    $sort = (Request::get('sort') !== '') ? Request::get('sort') : '';
    $queryString['sort'] = $sort;
    $queryString['direction'] = $direction;
    return $klass->appends($queryString)->links();
}

function link_url($default_name, $display_name, $action_name)
{
    $routeResult = array();
    foreach (Route::getRoutes() as $route)
    {
        if ($route->getName() === $action_name)
            return link_to_route($route->getName(),$display_name);
    }
    return link_to("#$default_name" ,$display_name );
}

function get_all_index_routes()
{
    foreach (Route::getRoutes() as $route)
    {
        $routeName = $route->getName();

        if (!empty($routeName) && strpos($routeName, 'index') !== false && strpos($routeName, 'page') === false) {
            $obj = new StdClass();
            $obj->name = $routeName;
            $routeResult[] = $obj;
        }
    }
    return array_pluck($routeResult, 'name','name');
}


function immap_get_user()
{
    return Cache::get('current_user'.Session::get('user_id'),\Immap\Watchkeeper\User::with('roles')
                        ->where('id','=',Session::get('user_id'))
                        ->first());
}

function immap_put_cache($name,$value,$minutes = 60)
{
    Cache::put($name,$value,Carbon::now()->addMinutes($minutes));
}

function immap_to_datetime_string($datetime)
{
    return Carbon::parse($datetime)->toDayDateTimeString();
}
