<?php namespace Greenelf\Panel\libs;


class AuthAdmin{


	public function checkLoggedIn(){

            $temp = \Config::get('auth.model');
            \Config::set('auth.model', 'Greenelf\Panel\Admin');
            $access = !\Auth::guard('panel')->guest();
            \Config::set('auth.model', $temp);
            return $access;		  
	}
}

