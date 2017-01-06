define([
    "models/db/Invoice/InvoiceType"
], function(InvoiceType){
    "use strict";

    return class InvoiceTypeCollection extends app.BaseCollection
    {
        getModel() { return InvoiceType; }
        url() {return app.API + "DB/Invoice/InvoiceType"}
    }
});