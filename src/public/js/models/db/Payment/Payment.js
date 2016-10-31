define([
    "models/db/Payment/PaymentType",
    "models/db/Invoice/Invoice",
    "app"
], function(PaymentType,
            Invoice){
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

        parse(response)
        {
            if('PaymentType' in response)
            {
                response.PaymentType = new PaymentType(response.PaymentType, {parse: true});
            }
            
            if('Invoice' in response)
            {
                response.Invoice = new Invoice(response.Invoice, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});