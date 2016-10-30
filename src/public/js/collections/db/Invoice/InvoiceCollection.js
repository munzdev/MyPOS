define([
    "app",
    "models/db/Invoice/Invoice"
], function(app, Invoice){
    "use strict";
    
    return class InvoiceCollection extends Backbone.Collection
    {
        model() { return Invoice; }
        url() {return app.API + "DB/Invoice"}
    }
});