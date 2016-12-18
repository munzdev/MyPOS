define(["models/db/Invoice/InvoiceWarningType"
], function(InvoiceWarningType){
    "use strict";

    return class InvoiceWarningTypeCollection extends app.BaseCollection
    {
        getModel() { return InvoiceWarningType; }
        url() {return app.API + "DB/Invoice/InvoiceWarningType";}
    }
});