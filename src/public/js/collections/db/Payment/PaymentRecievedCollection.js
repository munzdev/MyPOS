define([
    "models/db/Payment/PaymentRecieved"
], function(PaymentRecieved){
    "use strict";

    return class PaymentRecievedCollection extends app.BaseCollection
    {
        getModel() { return PaymentRecieved; }
        url() {return app.API + "DB/PaymentRecieved";}
    }
});