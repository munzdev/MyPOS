define([
    "models/db/Payment/Coupon"
], function(Coupon){
    "use strict";
    
    return class CouponCollection extends Backbone.Collection
    {
        model() { return Coupon; }
        url() {return app.API + "DB/Payment/Coupon";}
    }
});