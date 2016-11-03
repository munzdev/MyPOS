define([
    "models/db/Payment/PaymentCoupon"
], function(PaymentCoupon){
    "use strict";
    
    return class PaymentCouponCollection extends app.BaseCollection
    {
        getModel() { return PaymentCoupon; }
        url() {return app.API + "DB/Payment/PaymentCoupon";}
    }
});