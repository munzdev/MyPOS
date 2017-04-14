<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Lib\Interfaces\Models\Menu\IMenuType;
use API\Models\Model;
use API\Models\ORM\Menu\MenuGroup as MenuGroupORM;

/**
 * Skeleton subclass for representing a row from the 'menu_group' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class MenuGroup extends Model implements IMenuGroup
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new MenuGroupORM());
    }

    public function getMenuGroupid(): int
    {
        return $this->model->getMenuGroupid();
    }

    public function getMenuType(): IMenuType
    {
        $menuType = $this->model->getMenuType();

        $menuTypeModel = $this->container->get(IMenuType::class);
        $menuTypeModel->setModel($menuType);

        return $menuTypeModel;
    }

    public function getMenuTypeid(): int
    {
        return $this->model->getMenuTypeid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function setMenuGroupid($menuGroupid): IMenuGroup
    {
        $this->model->setMenuGroupid($menuGroupid);
        return $this;
    }

    public function setMenuType($menuType): IMenuGroup
    {
        $this->model->setMenuType($menuType);
        return $this;
    }

    public function setMenuTypeid($menuTypeid): IMenuGroup
    {
        $this->model->setMenuTypeid($menuTypeid);
        return $this;
    }

    public function setName($name): IMenuGroup
    {
        $this->model->setName($name);
        return $this;
    }

}
