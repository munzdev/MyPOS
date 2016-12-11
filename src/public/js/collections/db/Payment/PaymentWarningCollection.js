define(["models/db/Payment/PaymentWarning"
], function(PaymentWarning){
    "use strict";

    return class PaymentWarningCollection extends app.BaseCollection
    {
        getModel() { return PaymentWarning; }
        url() {return app.API + "DB/Payment/PaymentWarning";}
    }
});