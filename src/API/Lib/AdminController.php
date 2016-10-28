<?php
namespace API\Lib;

use API\Lib\Exceptions\SecurityException;
use Slim\App;

abstract class AdminController extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $o_user = Auth::GetCurrentUser();

        if(!$o_user->getIsAdmin())
            throw new SecurityException("Access Denied!");
    }
}