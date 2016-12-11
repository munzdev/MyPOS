define([
    "models/db/Payment/PaymentType",
    "models/db/Invoice/Invoice",
    "collections/db/Payment/PaymentWarningCollection"
], function(PaymentType,
            Invoice,
            PaymentWarningCollection){
    "use strict";

    return class Payment extends app.BaseModel {

        idAttribute() { return 'Paymentid'; }

        defaults() {
            return {Paymentid: null,
                    PaymentTypeid: null,
                    Invoiceid: null,
                    Created: null,
                    Amount: 0,
                    MaturityDate: null,
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

            if('PaymentWarnings' in response)
            {
                if(response.PaymentWarnings.toString() == '')
                    response.PaymentWarnings = new PaymentWarningCollection();
                else
                    response.PaymentWarnings = new PaymentWarningCollection(response.PaymentWarnings, {parse: true});
            }

            return super.parse(response);
        }
    }
});