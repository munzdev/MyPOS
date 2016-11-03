define([
    "models/db/Invoice/InvoiceItem"
], function(InvoiceItem){
    "use strict";
    
    return class InvoiceItemCollection extends Backbone.Collection
    {
        model() { return InvoiceItem; }
        url() {return app.API + "DB/Invoice/InvoiceItem"}
    }
});