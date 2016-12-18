define([
    "models/db/Payment/PaymentType",
    "models/db/Invoice/Invoice"
], function(PaymentType,
            Invoice){
    "use strict";

    return class PaymentRecieved extends app.BaseModel {

        idAttribute() { return 'PaymentRecievedid'; }

        defaults() {
            return {PaymentRecievedid: null,
                    Invoiceid: null,
                    PaymentTypeid: null,
                    date: null,
                    amount: 0};
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