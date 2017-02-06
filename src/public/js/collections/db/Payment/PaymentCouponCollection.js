define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class PaymentCouponCollection extends BaseCollection
    {
        getModel() { return app.models.Payment.PaymentCoupon; }
        url() {return app.API + "DB/Payment/PaymentCoupon";}
    }
});