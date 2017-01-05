define(["collections/db/Invoice/InvoiceCollection"
], function(InvoiceCollection){
    "use strict";

    return class InvoiceOverviewCollection extends InvoiceCollection
    {
        url() {return app.API + "Invoice";}
        parse(response) {
            this.count = response.Count;
            response = response.Order;
            return super.parse(response);
        }
    }
});