define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class InvoiceCollection extends BaseCollection
    {
        getModel() { return app.models.Invoice.Invoice; }
        url() {return app.API + "DB/Invoice"}
    }
});