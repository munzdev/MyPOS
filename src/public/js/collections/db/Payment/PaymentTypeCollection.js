define([
    "models/db/Payment/PaymentType"
], function(PaymentType){
    "use strict";
    
    return class PaymentTypeCollection extends app.BaseCollection
    {
        getModel() { return PaymentType; }
        url() {return app.API + "DB/Payment/PaymentType";}
    }
});