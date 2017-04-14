<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningType;
use API\Models\Model;
use API\Models\ORM\Invoice\InvoiceWarningType as InvoiceWarningTypeORM;

/**
 * Skeleton subclass for representing a row from the 'invoice_warning_type' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class InvoiceWarningType extends Model implements IInvoiceWarningType
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new InvoiceWarningTypeORM());
    }

    public function getEvent(): IEvent
    {
        $event = $this->model->getEvent();

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }

    public function getEventid(): int
    {
        return $this->model->getEventid();
    }

    public function getExtraPrice(): float
    {
        return $this->model->getExtraPrice();
    }

    public function getInvoiceWarningTypeid(): int
    {
        return $this->model->getInvoiceWarningTypeid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function setEvent($event): IInvoiceWarningType
    {
        $this->model->setEvent($event);
        return $this;
    }

    public function setEventid($eventid): IInvoiceWarningType
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setExtraPrice($extraPrice): IInvoiceWarningType
    {
        $this->model->setExtraPrice($extraPrice);
        return $this;
    }

    public function setInvoiceWarningTypeid($invoiceWarningTypeid): IInvoiceWarningType
    {
        $this->model->setInvoiceWarningTypeid($invoiceWarningTypeid);
        return $this;
    }

    public function setName($name): IInvoiceWarningType
    {
        $this->model->setName($name);
        return $this;
    }

}
