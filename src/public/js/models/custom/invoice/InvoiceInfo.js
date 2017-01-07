define(["models/db/Invoice/Invoice",
], function(Invoice){
    "use strict";

    return class InvoiceInfo extends Invoice
    {
        urlRoot() { return app.API + "Invoice/Info"; }
    }
});