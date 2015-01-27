<?php

class RemindersController extends Controller {

    /**
     * Display the password reminder view.
     *
     * @return Response
     */
    public function getRemind()
    {
        return View::make('password.remind');
    }

    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */
    public function postRemind()
    {
        $rules = array('email' => 'required|email');

        $validator = Validator::make(Input::only('email'), $rules);
        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator);
        }
        else
        {
            switch ($response = Password::remind(Input::only('email'),function($message) {
                $message->subject('Password Reminder');
            }))
            {
                case Password::INVALID_USER:
                    return Redirect::back()->with('error', Lang::get($response));

                case Password::REMINDER_SENT:
                    return Redirect::back()->with('status', Lang::get($response));
            }
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) App::abort(404);

        return View::make('password.reset')->with('token', $token);
    }

    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Response
     */
    public function postReset()
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|min:8|max:20|confirmed',
            'password_confirmation' => 'required'
            );
        $credentials = Input::only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $validator = Validator::make($credentials, $rules);
        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput(Input::only('email'));
        }
        $response = Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);

            $user->save();
        });

        switch ($response)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));

            case Password::PASSWORD_RESET:
                return Redirect::to('/');
        }
    }

}
