<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceCollection;
use API\Lib\Interfaces\Models\Invoice\IInvoiceQuery;
use API\Models\ORM\Invoice\InvoiceQuery as InvoiceQueryORM;
use API\Models\Query;
use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;

class InvoiceQuery extends Query implements IInvoiceQuery
{
    public function find(): IInvoiceCollection
    {
        $invoices = InvoiceQueryORM::create()->find();

        $invoiceCollection = $this->container->get(IInvoiceCollection::class);
        $invoiceCollection->setCollection($invoices);

        return $invoiceCollection;
    }

    public function findPk($id): ?IInvoice
    {
        $invoice = InvoiceQueryORM::create()->findPk($id);

        if(!$invoice) {
            return null;
        }

        $invoiceModel = $this->container->get(IInvoice::class);
        $invoiceModel->setModel($invoice);

        return $invoiceModel;
    }

    private function getSearchCriteria($eventid, $status, $invoiceid, $customerid, $canceled, $typeid, $userid, $dateFrom, $dateTo) : InvoiceQueryORM
    {
        return InvoiceQueryORM::create()
                ->useEventContactRelatedByEventContactidQuery()
                    ->filterByEventid($eventid)
                ->endUse()
                ->_if($status == 'paid')
                    ->filterByPaymentFinished(null, Criteria::NOT_EQUAL)
                ->_elseif($status == 'unpaid')
                    ->filterByPaymentFinished(null)
                ->_endif()
                ->_if($invoiceid)
                    ->filterByInvoiceid($invoiceid)
                ->_endif()
                ->_if($customerid)
                    ->filterByCustomerEventContactid($customerid)
                ->_endif()
                ->_if($canceled === true)
                    ->filterByCanceledInvoiceid(null, Criteria::NOT_EQUAL)
                ->_elseif($canceled === false)
                    ->filterByCanceledInvoiceid(null)
                ->_endif()
                ->_if($typeid)
                    ->filterByInvoiceTypeid($typeid)
                ->_endif()
                ->_if($userid != '*')
                    ->filterByUserid($userid)
                ->_endif()
                ->_if($dateFrom)
                    ->filterByDate(array('min' => new DateTime($dateFrom)))
                ->_endif()
                ->_if($dateTo)
                    ->filterByDate(array('max' => new DateTime($dateTo)))
                ->_endif()
                ->orderByDate()
                ->joinWithInvoiceType();
    }

    public function getInvoiceCountBySearch($eventid, $status, $invoiceid, $customerid, $canceled, $typeid, $userid, $dateFrom, $dateTo) : int
    {
        $searchCriteria = $this->getSearchCriteria($eventid, $status, $invoiceid, $customerid, $canceled, $typeid, $userid, $dateFrom, $dateTo);

        return $searchCriteria->count();
    }

    public function findWithPagingAndSearch($offset, $limit, $eventid, $status, $invoiceid, $customerid, $canceled, $typeid, $userid, $dateFrom, $dateTo) : IInvoiceCollection
    {
        $searchCriteria = $this->getSearchCriteria($eventid, $status, $invoiceid, $customerid, $canceled, $typeid, $userid, $dateFrom, $dateTo);

        $invoices = $searchCriteria
            ->_if($offset && $limit)
                ->offset($offset)
                ->limit($limit)
            ->_endif()
            ->find();

        $invoiceCollection = $this->container->get(IInvoiceCollection::class);
        $invoiceCollection->setCollection($invoices);

        return $invoiceCollection;
    }

    public function getWithDetails(int $eventid, int $invoiceid) : ?IInvoice
    {
        $invoice = InvoiceQueryORM::create()
            ->useEventContactRelatedByEventContactidQuery()
                ->filterByEventid($eventid)
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
            //->setFormatter(ModelCriteria::FORMAT_ARRAY)
            ->findByInvoiceid($invoiceid)
            ->getFirst();

        if(!$invoice) {
            return null;
        }

        $invoiceModel = $this->container->get(IInvoice::class);
        $invoiceModel->setModel($invoice);

        return $invoiceModel;
    }

    public function getWithItems(int $invoiceid) : ?IInvoice
    {
        $invoice = InvoiceQueryORM::create()
            ->innerJoinWithInvoiceItem()
            ->filterByInvoiceid($invoiceid)
            ->find()
            ->getFirst();

        if(!$invoice) {
            return null;
        }

        $invoiceModel = $this->container->get(IInvoice::class);
        $invoiceModel->setModel($invoice);

        return $invoiceModel;
    }
}
