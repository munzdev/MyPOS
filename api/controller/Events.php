<?php

class Events extends SecurityController
{
	public function GetPrintersAction()
	{
		$o_events = new Model\Events(Database::GetConnection());

		$a_printers = $o_events->GetPrinters(Login::GetCurrentUser()['eventid']);

		return $a_printers;
	}
}