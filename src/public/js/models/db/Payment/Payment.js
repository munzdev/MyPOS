define([
    "models/db/Payment/PaymentType",
    "models/db/Invoice/Invoice",
    
], function(PaymentType,
            Invoice){
    "use strict";

    return class Payment extends app.BaseModel {
        
        idAttribute() { return 'Paymentid'; }

        defaults() {
            return {Paymentid: null,
                    PaymentTypeid: null,
                    Invoiceid: null,
                    Created: null,
                    Amount: 0,
                    Canceled: null,
                    Recieved: null,
                    AmountRecieved: 0};
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