define([
    "models/db/Invoice/Invoice"
], function(Invoice){
    "use strict";
    
    return class InvoiceCollection extends app.BaseCollection
    {
        getModel() { return Invoice; }
        url() {return app.API + "DB/Invoice"}
    }
});