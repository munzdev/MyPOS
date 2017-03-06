<?php

namespace API\Models\Invoice;

use API\Lib\Interfaces\Models\Invoice\IInvoiceItem;
use API\Models\Invoice\Base\InvoiceItem as BaseInvoiceItem;

/**
 * Skeleton subclass for representing a row from the 'invoice_item' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class InvoiceItem extends BaseInvoiceItem implements IInvoiceItem
{
}
