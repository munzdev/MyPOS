<?php
namespace API\Lib;

use API\Lib\Exceptions\SecurityException;
use API\Lib\Interfaces\IAuth;
use Slim\App;

abstract class AdminController extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $auth = $this->container->get(IAuth::class);
        $user = $auth->getCurrentUser();

        if (!$user->getIsAdmin()) {
            throw new SecurityException("Access Denied!");
        }
    }
}
