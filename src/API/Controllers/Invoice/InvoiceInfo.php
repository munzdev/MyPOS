<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Models\ORM\Invoice\InvoiceQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_INVOICE_OVERVIEW;

class InvoiceInfo extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_INVOICE_OVERVIEW];

        $app->getContainer()['db'];
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
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $invoice = InvoiceQuery::create()
                                ->useEventContactRelatedByEventContactidQuery()
                                    ->filterByEventid($user->getEventUsers()->getFirst()->getEventid())
                                ->endUse()
                                ->joinWithInvoiceType()
                                ->joinWith('EventContactRelatedByEventContactid contact')
                                ->joinWith('EventContactRelatedByCustomerEventContactid customer', Criteria::LEFT_JOIN)
                                ->joinWithUser()
                                ->joinWithEventBankinformation()
                                ->joinWithInvoiceItem()
                                ->leftJoinWithPaymentRecieved()
                                ->usePaymentRecievedQuery(null, Criteria::LEFT_JOIN)
                                    ->leftJoinWithPaymentType()
                                    ->joinWith('User paymentUser', Criteria::LEFT_JOIN)
                                    ->leftJoinWithPaymentCoupon()
                                    ->usePaymentCouponQuery(null, Criteria::LEFT_JOIN)
                                        ->leftJoinWithCoupon()
                                    ->endUse()
                                ->endUse()
                                ->leftJoinWithInvoiceWarning()
                                ->useInvoiceWarningQuery(null, Criteria::LEFT_JOIN)
                                    ->leftJoinWithInvoiceWarningType()
                                ->endUse()
                                ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                ->findByInvoiceid($this->args['id'])
                                ->getFirst();

        $this->withJson($invoice);
    }
}
