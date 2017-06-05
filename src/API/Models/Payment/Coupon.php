<?php

namespace API\Models\Payment;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Payment\ICoupon;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use API\Models\ORM\Payment\Coupon as CouponORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'coupon' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Coupon extends Model implements ICoupon
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new CouponORM());
    }

    public function getCode(): string
    {
        return $this->model->getCode();
    }

    public function getCouponid(): int
    {
        return $this->model->getCouponid();
    }

    public function getCreated(): DateTime
    {
        return $this->model->getCreated();
    }

    public function getCreatedByUser(): IUser
    {
        $user = $this->model->getCreatedByUser();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getCreatedByUserid(): int
    {
        return $this->model->getCreatedByUserid();
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

    public function getValue(): float
    {
        return $this->model->getValue();
    }

    public function getIsDeleted() : ?DateTime
    {
        return $this->model->getIsDeleted();
    }

    public function setCode($code): ICoupon
    {
        $this->model->setCode($code);
        return $this;
    }

    public function setCouponid($couponid): ICoupon
    {
        $this->model->setCouponid($couponid);
        return $this;
    }

    public function setCreated($created): ICoupon
    {
        $this->model->setCreated($created);
        return $this;
    }

    public function setCreatedByUser($user): ICoupon
    {
        $this->model->setCreatedByUser($user);
        return $this;
    }

    public function setCreatedByUserid($userid): ICoupon
    {
        $this->model->setCreatedByUserid($userid);
        return $this;
    }

    public function setEvent($event): ICoupon
    {
        $this->model->setEvent($event);
        return $this;
    }

    public function setEventid($eventid): ICoupon
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setValue($value): ICoupon
    {
        $this->model->setValue($value);
        return $this;
    }

    public function setIsDeleted($isDeleted): ICoupon
    {
        $this->model->setIsDeleted($isDeleted);
        return $this;
    }
}
