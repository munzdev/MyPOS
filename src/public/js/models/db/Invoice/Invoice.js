define([
    "app"
], function(app){
    "use strict";

    return class Invoice extends Backbone.Model {
        
        idAttribute() { return 'Invoiceid'; }

        defaults() {
            return {Invoiceid: 0,
                    CashierUserid: 0,
                    Date: null,
                    Canceled: null};
        }

    }
});