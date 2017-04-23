<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\SecurityController;
use API\Models\ORM\Event\EventContact;
use Slim\App;

class Customer extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function post() : void
    {
        $auth = $this->container->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $customer = $this->json;

        $eventContact = $this->container->get(IEventContact::class);

        $eventContact->setTitle($customer['Title'])
            ->setName($customer['Name'])
            ->setContactPerson($customer['ContactPerson'])
            ->setAddress($customer['Address'])
            ->setAddress2($customer['Address2'])
            ->setCity($customer['City'])
            ->setZip($customer['Zip'])
            ->setTaxIdentificationNr($customer['TaxIdentificationNr'])
            ->setTelephon($customer['Telephon'])
            ->setFax($customer['Fax'])
            ->setEmail($customer['Email'])
            ->setActive(true)
            ->setEventid($user->getEventUsers()->getFirst()->getEventid())
            ->save();

        $this->withJson($eventContact->toArray());
    }
}
