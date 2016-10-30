define([
    "app"
], function(app){
    "use strict";

    return class Payment extends Backbone.Model {
        
        idAttribute() { return 'Paymentid'; }

        defaults() {
            return {Paymentid: 0,
                    PaymentTypeid: 0,
                    Invoiceid: 0,
                    Date: null,
                    Amount: 0,
                    Canceled: false};
        }

    }
});