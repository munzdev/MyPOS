define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class InvoiceItemCollection extends BaseCollection
    {
        getModel() { return app.models.Invoice.InvoiceItem; }
        url() {return app.API + "DB/Invoice/InvoiceItem"}
    }
});