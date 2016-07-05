<?php 

class Login
{
	private $o_users;
	
	public function __construct(Model\Users $o_users)
	{
		$this->o_users = $o_users;		
	}
	
	public function DoLogin($str_username, $b_remember_me = false)
	{
		$a_user = $this->o_users->GetUserDetailsByUsername($str_username);
		
		if(empty($a_user))
		{
			$a_user = $this->o_users->GetAdminDetailsByUsername($str_username);
		}
			
		if($a_user)
        {
             	$this->SetLogin($a_user);

                if($b_remember_me)
                {
                	$o_rememberMe = new RememberMe($this->o_users, $GLOBALS['a_config']['Auth']['RememberMe_PrivateKey']);
                	$o_rememberMe->remember($a_user['userid']);                			
                }
                
                return true;                         
         }
         else
         {
         	return false;
         }                
	}
	
	public function CheckLogin($str_username, $str_password, $b_remember_me)
	{
		$a_user = $this->o_users->GetUserDetailsByUsername($str_username);
		
		if(empty($a_user))
		{
			$a_user = $this->o_users->GetAdminDetailsByUsername($str_username);
		}
			
		if($a_user)
		{
			if(md5($str_password) == $a_user['password'])
			{
				$this->DoLogin($str_username, $b_remember_me);	
		
				return true;
			}
			else
			{
				return false;
			}
		}
		 
	}
	
	public function SetLogin(array $a_user)
	{
		$_SESSION['Login'] = $a_user;
	}
	
	public static function GetCurrentUser()
	{
		return isset($_SESSION['Login']) ? $_SESSION['Login'] : null;
	}
	
	public static function IsLoggedIn()
	{
		return isset($_SESSION['Login']);
	}
	
	public function Logout()
	{
		RememberMe::Destroy();
		$_SESSION['Login'] = null;
		unset($_SESSION['Login']);
	}			
}