define(["models/db/Payment/PaymentRecieved"
], function(PaymentRecieved){
    "use strict";

    return class PaymentRecievedModel extends PaymentRecieved {
        urlRoot() { return app.API + "Payment"; }
    }
});