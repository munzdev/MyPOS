define(["models/db/Invoice/Invoice"
], function(Invoice){
    "use strict";

    return class InvoiceOverviewCollection extends app.BaseCollection
    {
        getModel() { return Invoice; }
        url() {return app.API + "Invoice";}
        parse(response) {
            this.count = response.Count;
            response = response.Invoice;
            return super.parse(response);
        }
    }
});