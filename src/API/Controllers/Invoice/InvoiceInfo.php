<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Invoice\InvoiceQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_INVOICE_OVERVIEW;

class InvoiceInfo extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['GET' => USER_ROLE_INVOICE_OVERVIEW];

        $o_app->getContainer()['db'];
    }

    function ANY() : void {
        $a_validators = array(
            'id' => v::intVal()->positive()
        );

        $this->validate($a_validators, $this->a_args);
    }

    protected function GET() : void
    {
        $o_user = Auth::GetCurrentUser();

        $a_invoice = InvoiceQuery::create()
                                    ->useEventContactRelatedByEventContactidQuery()
                                        ->filterByEventid($o_user->getEventUser()->getEventid())
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
                                    ->findByInvoiceid($this->a_args['id'])
                                    ->getFirst();

        $this->withJson($a_invoice);
    }
}