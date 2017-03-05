<?php

namespace API\Lib\Interfaces\Models\Payment;

use API\Lib\Interfaces\Models\IModel;

interface IPaymentType extends IModel {
    /**
     * @return int
     */
    function getPaymentTypeid();

    /**
     * @return string
     */
    function getName();

    /**
     *
     * @param int $paymentTypeid Description
     * @return IPaymentType Description
     */
    function setPaymentTypeid($paymentTypeid);

    /**
     *
     * @param string $name Description
     * @return IPaymentType Description
     */
    function setName($name);
}