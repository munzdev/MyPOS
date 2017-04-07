<?php

namespace API\Lib\Interfaces\Models\Payment;

interface IPaymentTypeQuery {
    function find() : IPaymentTypeCollection;
}