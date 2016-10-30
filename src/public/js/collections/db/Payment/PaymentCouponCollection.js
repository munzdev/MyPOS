define([
    "app",
    "models/db/Payment/PaymentCoupon"
], function(app, PaymentCoupon){
    "use strict";
    
    return class PaymentCouponCollection extends Backbone.Collection
    {
        model() { return PaymentCoupon; }
        url() {return app.API + "DB/Payment/PaymentCoupon";}
    }
});