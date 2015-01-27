<?php

class AuthController extends \BaseController {
    public function showLogin()
    {
         if (Auth::check() && Session::has('current_role_id'))
         {
            return Redirect::route('admin.main');
         }
        // Show the login page
        return View::make('auth/login');
    }

    public function postLogin()
    {
         $rules = array(
            'username'  => 'required',
            'password'  => 'required',
        );
        $data = Input::only('username', 'password');
        $validator = Validator::make($data, $rules);
        if ($validator->fails())
        {
           return Redirect::route('auth.login')->withErrors( $validator )->withInput(Input::except('password'));
        }
        else
        {
            if (Auth::attempt($data))
            {
                $user = \Immap\Watchkeeper\User::with('roles')
                        ->where('username','=',$data['username'])
                        ->first();
                Session::put('user_id', $user->id);
                if ($user->roles->count() > 0) {
                    Session::put('current_role_id', $user->roles[0]->id);
                }
                immap_put_cache('current_user'.Session::get('user_id'),$user);
                return Redirect::route('admin.main')->with('login.success', 'You have logged in successfully');
            }
            else
            {
                return Redirect::route('auth.login')->withErrors(array('password' => 'Username or Password are invalid'))->withInput(Input::except('password'));
            }
        }
    }

    public function getLogout()
    {
        Cache::forget('current_menu_'.Session::get('user_id'));
        Cache::forget('current_group_prefix_'.Session::get('user_id'));
        Session::forget('user_id');
        Session::forget('current_role_id');
        Auth::logout();
        return Redirect::route('auth.login')->with('login.success', 'You are logged out');
    }

    public function getSecLogout()
    {
        return $this->getLogout();
    }
}
