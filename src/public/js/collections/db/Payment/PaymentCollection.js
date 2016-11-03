define([
    "models/db/Payment/Payment"
], function(Payment){
    "use strict";
    
    return class PaymentCollection extends app.BaseCollection
    {
        getModel() { return Payment; }
        url() {return app.API + "DB/Payment";}
    }
});