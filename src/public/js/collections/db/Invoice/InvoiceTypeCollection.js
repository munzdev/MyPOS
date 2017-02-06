define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class InvoiceTypeCollection extends BaseCollection
    {
        getModel() { return app.models.Invoice.InvoiceType; }
        url() {return app.API + "DB/Invoice/InvoiceType"}
    }
});