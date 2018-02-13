<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Models\Model;
use API\Models\ORM\Event\EventContact as EventContactORM;

/**
 * Skeleton subclass for representing a row from the 'event_contact' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class EventContact extends Model implements IEventContact
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new EventContactORM());
    }

    public function getIsDeleted(): ?\DateTime
    {
        return $this->model->getIsDeleted();
    }

    public function getAddress(): string
    {
        return $this->model->getAddress();
    }

    public function getAddress2(): ?string
    {
        return $this->model->getAddress2();
    }

    public function getCity(): string
    {
        return $this->model->getCity();
    }

    public function getContactPerson(): ?string
    {
        return $this->model->getContactPerson();
    }

    public function getDefault(): boolean
    {
        return $this->model->getDefault();
    }

    public function getEmail(): string
    {
        return $this->model->getEmail();
    }

    public function getEvent(): IEvent
    {
        $event = $this->model->getEvent();

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }

    public function getEventContactid(): int
    {
        return $this->model->getEventContactid();
    }

    public function getEventid(): int
    {
        return $this->model->getEventid();
    }

    public function getFax(): string
    {
        return $this->model->getFax();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function getTaxIdentificationNr(): string
    {
        return $this->model->getTaxIdentificationNr();
    }

    public function getTelephon(): string
    {
        return $this->model->getTelephon();
    }

    public function getTitle(): string
    {
        return $this->model->getTitle();
    }

    public function getZip(): string
    {
        return $this->model->getZip();
    }

    public function setIsDeleted($isDeleted): IEventContact
    {
        $this->model->setIsDeleted($isDeleted);
        return $this;
    }

    public function setAddress($address): IEventContact
    {
        $this->model->setAddress($address);
        return $this;
    }

    public function setAddress2($address2): IEventContact
    {
        $this->model->setAddress2($address2);
        return $this;
    }

    public function setCity($city): IEventContact
    {
        $this->model->setCity($city);
        return $this;
    }

    public function setContactPerson($contactPerson): IEventContact
    {
        $this->model->setContactPerson($contactPerson);
        return $this;
    }

    public function setDefault($default): IEventContact
    {
        $this->model->setDefault($default);
        return $this;
    }

    public function setEmail($email): IEventContact
    {
        $this->model->setEmail($email);
        return $this;
    }

    public function setEvent($event): IEventContact
    {
        $this->model->setEvent($event->getModel());
        return $this;
    }

    public function setEventContactid($eventContactid): IEventContact
    {
        $this->model->setEventContactid($eventContactid);
        return $this;
    }

    public function setEventid($eventid): IEventContact
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setFax($fax): IEventContact
    {
        $this->model->setFax($fax);
        return $this;
    }

    public function setName($name): IEventContact
    {
        $this->model->setName($name);
        return $this;
    }

    public function setTaxIdentificationNr($taxIdentificationNr): IEventContact
    {
        $this->model->setTaxIdentificationNr($taxIdentificationNr);
        return $this;
    }

    public function setTelephon($telephon): IEventContact
    {
        $this->model->setTelephon($telephon);
        return $this;
    }

    public function setTitle($title): IEventContact
    {
        $this->model->setTitle($title);
        return $this;
    }

    public function setZip($zip): IEventContact
    {
        $this->model->setZip($zip);
        return $this;
    }
}
