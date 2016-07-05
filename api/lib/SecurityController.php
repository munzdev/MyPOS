<?php

class SecurityController extends Controller
{
	protected $o_login;

	public function __construct()
	{
            parent::__construct();
            
            if(!Login::IsLoggedIn())
            {
                throw new Exception("Zugriff verweigert!");
            }
	}

	public function CheckAccess($str_action)
	{
		return true;
	}
}