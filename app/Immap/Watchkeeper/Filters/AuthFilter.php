<?php namespace Immap\Watchkeeper\Filters;
class AuthFilter {

    public function manageMenus()
    {
        if (\Session::has('user_id'))
        {
            $user = \Immap\Watchkeeper\User::find(\Session::get('user_id'));
            $menu = \Cache::remember('current_menu_'.\Session::get('user_id'), \Carbon::now()->addMinutes(20), function()
            {
                return \DB::table('users')
                ->join('assigned_roles', 'users.id','=', 'assigned_roles.user_id')
                ->join('permission_roles', 'assigned_roles.role_id','=', 'permission_roles.role_id')
                ->join('permissions', 'permissions.id','=', 'permission_roles.permission_id')
                ->where('users.id',\Session::get('user_id'))
                ->where('assigned_roles.role_id',\Session::get('current_role_id'))
                ->orderby('permissions.group_name')
                ->orderby('permissions.display_name')
                ->select('users.id','users.username','permissions.name', 'permissions.display_name', 'permissions.group_name', 'permissions.action_name')->get();
            });

            \Cache::remember('current_group_prefix_'.\Session::get('user_id'), \Carbon::now()->addMinutes(20), function()
            {
                $prefixs = array();
                foreach (\Cache::get('current_menu_'.\Session::get('user_id')) as $m)
                {
                    foreach (\Route::getRoutes() as $r)
                    {
                        $routeName = $r->getName();
                        if (!empty($routeName) &&
                            strpos($routeName, 'index') !== false &&
                            strpos($routeName, 'page') === false &&
                            $m->action_name === $routeName)
                        {
                            $prefixs[] = $r->getPrefix();
                        }
                    }
                }
                return $prefixs;
            });

            $oldgroup = '';

            \View::composer('layouts.master', function ($view) use ($user,$menu,$oldgroup) {
                $view->with('menu',$menu)->with('user',$user)->with('oldgroup',$oldgroup);
            });
        }
        else
        {
            return \Redirect::route('auth.login');
        }
    }

    public function managePermissions($route, $request)
    {
        if (\Session::has('user_id'))
        {
            $menus = \Cache::get('current_menu_'.\Session::get('user_id'));
            $prefixs = \Cache::get('current_group_prefix_'.\Session::get('user_id'));

            $passURL = in_array($route->getPrefix(), $prefixs);
            $passMainAdmin = $route->getPrefix() == 'admin' && count($prefixs) > 0;
            if( !($passURL || $passMainAdmin))
            {
                return \Redirect::route('auth.login');
            }
        }
        else
        {
            return \Redirect::route('auth.login');
        }
    }

}
