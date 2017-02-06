define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class InvoiceOverviewCollection extends BaseCollection
    {
        getModel() { return app.models.Invoice.Invoice; }
        url() {return app.API + "Invoice";}
        parse(response) {
            this.count = response.Count;
            response = response.Invoice;
            return super.parse(response);
        }
    }
});