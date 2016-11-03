define([
    "models/db/Invoice/Invoice"
], function(Invoice){
    "use strict";
    
    return class InvoiceCollection extends Backbone.Collection
    {
        model() { return Invoice; }
        url() {return app.API + "DB/Invoice"}
    }
});