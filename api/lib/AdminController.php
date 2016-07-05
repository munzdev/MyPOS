<?php

class AdminController extends SecurityController
{
	public function __construct()
	{
		parent::__construct();
		
		$a_user = Login::GetCurrentUser();
		
		if(!$a_user['is_admin'])
			throw new Exception("Zugriff verweigert!");
	}
}