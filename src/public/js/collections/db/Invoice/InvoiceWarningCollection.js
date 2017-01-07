define(["models/db/Invoice/InvoiceWarning"
], function(InvoiceWarning){
    "use strict";

    return class InvoiceWarningCollection extends app.BaseCollection
    {
        getModel() { return InvoiceWarning; }
        url() {return app.API + "DB/Invoice/InvoiceWarning";}
    }
});