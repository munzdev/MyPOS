define([
    "models/db/Payment/Coupon"
], function(Coupon){
    "use strict";
    
    return class CouponCollection extends app.BaseCollection
    {
        getModel() { return Coupon; }
        url() {return app.API + "DB/Payment/Coupon";}
    }
});