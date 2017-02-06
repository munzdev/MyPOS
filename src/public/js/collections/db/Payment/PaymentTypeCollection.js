define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class PaymentTypeCollection extends BaseCollection
    {
        getModel() { return app.models.Payment.PaymentType; }
        url() {return app.API + "DB/Payment/PaymentType";}
    }
});