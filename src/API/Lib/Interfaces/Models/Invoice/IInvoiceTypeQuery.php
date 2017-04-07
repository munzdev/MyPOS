<?php

namespace API\Lib\Interfaces\Models\Invoice;

interface IInvoiceTypeQuery {
    function find() : IInvoiceTypeCollection;
}