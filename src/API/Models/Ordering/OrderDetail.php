<?php

namespace API\Models\Ordering;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItemCollection;
use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Lib\Interfaces\Models\Menu\IMenu;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\Menu\IMenuSize;
use API\Lib\Interfaces\Models\OIP\IOrderInProgressRecievedCollection;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailExtraCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailMixedWithCollection;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use API\Models\ORM\Ordering\OrderDetail as OrderDetailORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'order_detail' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class OrderDetail extends Model implements IOrderDetail
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new OrderDetailORM());
    }

    public function getAmount(): int
    {
        return $this->model->getAmount();
    }

    public function getAvailability(): IAvailability
    {
        $availability = $this->model->getAvailability();

        $availabilityModel = $this->container->get(IAvailability::class);
        $availabilityModel->setModel($availability);

        return $availabilityModel;
    }

    public function getAvailabilityAmount(): int
    {
        return $this->model->getAvailabilityAmount();
    }

    public function getAvailabilityid(): int
    {
        return $this->model->getAvailabilityid();
    }

    public function getDistributionFinished(): DateTime
    {
        return $this->model->getDistributionFinished();
    }

    public function getExtraDetail(): ?string
    {
        return $this->model->getExtraDetail();
    }

    public function getInvoiceFinished(): DateTime
    {
        return $this->model->getInvoiceFinished();
    }

    public function getMenu(): IMenu
    {
        $menu = $this->model->getMenu();

        $menuModel = $this->container->get(IMenu::class);
        $menuModel->setModel($menu);

        return $menuModel;
    }

    public function getMenuGroup(): IMenuGroup
    {
        $menuGroup = $this->model->getMenuGroup();

        $menuGroupModel = $this->container->get(IMenuGroup::class);
        $menuGroupModel->setModel($menuGroup);

        return $menuGroupModel;
    }

    public function getMenuGroupid(): int
    {
        return $this->model->getMenuGroupid();
    }

    public function getMenuSize(): IMenuSize
    {
        $menuSize = $this->model->getMenuSize();

        $menuSizeModel = $this->container->get(IMenuSize::class);
        $menuSizeModel->setModel($menuSize);

        return $menuSizeModel;
    }

    public function getMenuSizeid(): ?int
    {
        return $this->model->getMenuSizeid();
    }

    public function getMenuid(): ?int
    {
        return $this->model->getMenuid();
    }

    public function getOrder(): IOrder
    {
        $order = $this->model->getOrder();

        $orderModel = $this->container->get(IOrder::class);
        $orderModel->setModel($order);

        return $orderModel;
    }

    public function getOrderDetailid(): int
    {
        return $this->model->getOrderDetailid();
    }

    public function getOrderid(): int
    {
        return $this->model->getOrderid();
    }

    public function getSinglePrice(): float
    {
        return $this->model->getSinglePrice();
    }

    public function getSinglePriceModifiedByUser(): IUser
    {
        $user = $this->model->getSinglePriceModifiedByUser();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getSinglePriceModifiedByUserid(): int
    {
        return $this->model->getSinglePriceModifiedByUserid();
    }

    public function getVerified(): boolean
    {
        return $this->model->getVerified();
    }

    public function getOrderDetailExtras() : IOrderDetailExtraCollection
    {
        $orderDetailExtras = $this->model->getOrderDetailExtras();

        $orderDetailExtraCollection = $this->container->get(IOrderDetailExtraCollection::class);
        $orderDetailExtraCollection->setCollection($orderDetailExtras);

        return $orderDetailExtraCollection;
    }

    public function getOrderDetailMixedWiths() : IOrderDetailMixedWithCollection
    {
        $orderDetailMixedWiths = $this->model->getOrderDetailMixedWiths();

        $orderDetailMixedWithCollection = $this->container->get(IOrderDetailMixedWithCollection::class);
        $orderDetailMixedWithCollection->setCollection($orderDetailMixedWiths);

        return $orderDetailMixedWithCollection;
    }

    public function getOrderInProgressRecieveds() : IOrderInProgressRecievedCollection
    {
        $orderInProgressRecieveds = $this->model->getOrderInProgressRecieveds();

        $orderInProgressRecievedCollection = $this->container->get(IOrderInProgressRecievedCollection::class);
        $orderInProgressRecievedCollection->setCollection($orderInProgressRecieveds);

        return $orderInProgressRecievedCollection;
    }

    public function getInvoiceItems() : IInvoiceItemCollection
    {
        $invoiceItems = $this->model->getInvoiceItems();

        $invoiceItemCollection = $this->container->get(IInvoiceItemCollection::class);
        $invoiceItemCollection->setCollection($invoiceItems);

        return $invoiceItemCollection;
    }

    public function setAmount($amount): IOrderDetail
    {
        $this->model->setAmount($amount);
        return $this;
    }

    public function setAvailability($availability): IOrderDetail
    {
        $this->model->setAvailability($availability->getModel());
        return $this;
    }

    public function setAvailabilityAmount($availabilityAmount): IOrderDetail
    {
        $this->model->setAvailabilityAmount($availabilityAmount);
        return $this;
    }

    public function setAvailabilityid($availabilityid): IOrderDetail
    {
        $this->model->setAvailabilityid($availabilityid);
        return $this;
    }

    public function setDistributionFinished($distributionFinished): IOrderDetail
    {
        $this->model->setDistributionFinished($distributionFinished);
        return $this;
    }

    public function setExtraDetail($extraDetail): IOrderDetail
    {
        $this->model->setExtraDetail($extraDetail);
        return $this;
    }

    public function setInvoiceFinished($invoiceFinished): IOrderDetail
    {
        $this->model->setInvoiceFinished($invoiceFinished);
        return $this;
    }

    public function setMenu($menu): IOrderDetail
    {
        if ($menu == null) {
            $this->model->setMenu(null);
        } else {
            $this->model->setMenu($menu->getModel());
        }
        
        return $this;
    }

    public function setMenuGroup($menuGroup): IOrderDetail
    {
        if ($menuGroup == null) {
            $this->model->setMenuGroup(null);
        } else {
            $this->model->setMenuGroup($menuGroup->getModel());
        }
        
        return $this;
    }

    public function setMenuGroupid($menuGroupid): IOrderDetail
    {
        $this->model->setMenuGroupid($menuGroupid);
        return $this;
    }

    public function setMenuSize($menuSize): IOrderDetail
    {
        if ($menuSize == null) {
            $this->model->setMenuSize(null);
        } else {
            $this->model->setMenuSize($menuSize->getModel());
        }
        
        return $this;
    }

    public function setMenuSizeid($menuSizeid): IOrderDetail
    {
        $this->model->setMenuSizeid($menuSizeid);
        return $this;
    }

    public function setMenuid($menuid): IOrderDetail
    {
        $this->model->setMenuid($menuid);
        return $this;
    }

    public function setOrder($order): IOrderDetail
    {
        $this->model->setOrder($order->getModel());
        return $this;
    }

    public function setOrderDetailid($orderDetailid): IOrderDetail
    {
        $this->model->setOrderDetailid($orderDetailid);
        return $this;
    }

    public function setOrderid($orderid): IOrderDetail
    {
        $this->model->setOrderid($orderid);
        return $this;
    }

    public function setSinglePrice($singlePrice): IOrderDetail
    {
        $this->model->setSinglePrice($singlePrice);
        return $this;
    }

    public function setSinglePriceModifiedByUser($user): IOrderDetail
    {
        $this->model->setSinglePriceModifiedByUser($user->getModel());
        return $this;
    }

    public function setSinglePriceModifiedByUserid($userid): IOrderDetail
    {
        $this->model->setSinglePriceModifiedByUserid($userid);
        return $this;
    }

    public function setVerified($verified): IOrderDetail
    {
        $this->model->setVerified($verified);
        return $this;
    }

}
