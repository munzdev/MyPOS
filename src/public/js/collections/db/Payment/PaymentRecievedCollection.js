define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class PaymentRecievedCollection extends BaseCollection
    {
        getModel() { return app.models.Payment.PaymentRecieved; }
        url() {return app.API + "DB/PaymentRecieved";}
    }
});