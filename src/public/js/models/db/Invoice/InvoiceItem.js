define([
    "app"
], function(app){
    "use strict";

    return class InvoiceItem extends Backbone.Model {
        
        idAttribute() { return 'InvoiceItemid'; }
    
        defaults() {
            return {InvoiceItemid: 0,
                    Invoiceid: 0,
                    OrderDetailid: 0,
                    Amount: 0,
                    Price: 0,
                    Description: '',
                    Tax: 0};
        }

    }
});