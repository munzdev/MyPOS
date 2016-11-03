define([
    "models/db/Invoice/InvoiceItem"
], function(InvoiceItem){
    "use strict";
    
    return class InvoiceItemCollection extends app.BaseCollection
    {
        getModel() { return InvoiceItem; }
        url() {return app.API + "DB/Invoice/InvoiceItem"}
    }
});