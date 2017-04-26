<?php

namespace API\Controllers\Order;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Ordering\IOrderQuery;
use API\Lib\SecurityController;
use API\Models\ORM\Invoice\Map\InvoiceItemTableMap;
use API\Models\ORM\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\ORM\Ordering\Map\OrderDetailTableMap;
use API\Models\ORM\Ordering\OrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_ORDER_OVERVIEW;

class OrderInfo extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_ORDER_OVERVIEW];

        $this->container->get(IConnectionInterface::class);
    }

    public function any() : void
    {
        $validators = array(
            'id' => v::intVal()->positive()
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    protected function get() : void
    {
        $orderQuery = $validate = $this->container->get(IOrderQuery::class);;

        $orderDetails = $orderQuery->getDetails($this->args['id']);
        $orderDetailInfo = $orderQuery->getOrderDetails($this->args['id']);

        $orderDetailInfoArray = $orderDetailInfo->toArray();

        $orderDetailInfoArray['price'] = $orderDetails->price;
        $orderDetailInfoArray['open'] = $orderDetails->open;
        $orderDetailInfoArray['amountBilled'] = $orderDetails->amountBilled;

        // Dont send secure critical datas
        $orderDetailInfoArray['User'] = $this->cleanupUserData($orderDetailInfoArray['User']);

        $this->withJson($orderDetailInfoArray);
    }
}
