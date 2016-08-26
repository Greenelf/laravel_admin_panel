<?php

namespace Greenelf\Panel;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session as session;

class AuthController extends Controller
{
    /**
     * Display the password reminder view.
     *
     * @return Response
     */
    public function postLogin()
    {
        $userdata = array(
            'email' => Input::get('email'),
            'password' => Input::get('password')
        );
        // attempt to do the login
        if (\Auth::guard('panel')->attempt($userdata, filter_var(Input::get('remember'), FILTER_VALIDATE_BOOLEAN))) {
            return \Redirect::to('panel');
        } else {
            // validation not successful, send back to form	
            return \Redirect::to('panel/login')->with('message', \Lang::get('panel::fields.passwordNotCorrect'))->with('mesType', 'error');
        }
    }

    public function getLogin()
    {
        $message = (\Session::has('message') ? \Session::get('message') : \Lang::get('panel::fields.signIn'));
        $mesType = (\Session::has('mesType') ? \Session::get('mesType') : 'message');
        return view('panelViews::login')->with('message', $message)->with('mesType', $mesType);
    }

    public function doLogout()
    {
        \Auth::guard('panel')->logout();
        return \Redirect::to('panel/login');
    }
}