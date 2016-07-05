<?php

class Controller
{
	protected $b_raw_data = false;
	
	protected $a_vars;
	
	public function __construct()
	{
		$this->a_vars = $GLOBALS['a_vars'];
	}
	
	public function GetRawData()
	{
		return $this->b_raw_data;
	}
}