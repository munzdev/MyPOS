define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class InvoiceWarningCollection extends BaseCollection
    {
        getModel() { return app.models.Invoice.InvoiceWarning; }
        url() {return app.API + "DB/Invoice/InvoiceWarning";}
    }
});