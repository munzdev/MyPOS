define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class InvoiceWarningTypeCollection extends BaseCollection
    {
        getModel() { return app.models.Invoice.InvoiceWarningType; }
        url() {return app.API + "DB/Invoice/InvoiceWarningType";}
    }
});