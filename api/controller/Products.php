<?php

class Products extends SecurityController
{
	public function GetListAction()
	{
		$o_products = new Model\Products(Database::GetConnection());
			
		$a_products = $o_products->GetList(Login::GetCurrentUser()['eventid']);	
		
		return $a_products;
	}
}