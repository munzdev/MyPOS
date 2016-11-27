define(["models/db/Invoice/Invoice"
], function(InvoiceModel) {
    "use strict";

    return class Invoice extends InvoiceModel {
        urlRoot() { return app.API + "Invoice"; }
    }
});