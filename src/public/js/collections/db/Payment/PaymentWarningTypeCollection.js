define([
    "models/db/Payment/PaymentWarningType"
], function(PaymentWarningType){
    "use strict";

    return class PaymentWarningTypeCollection extends app.BaseCollection
    {
        getModel() { return PaymentWarningType; }
        url() {return app.API + "DB/Payment/PaymentWarningType";}
    }
});