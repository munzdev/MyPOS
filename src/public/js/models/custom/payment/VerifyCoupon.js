define(["models/db/Payment/Coupon"    
], function(CouponModel){
    "use strict";

    return class VerifyCoupon extends CouponModel {        
        idAttribute() { return 'Code'; }        
        urlRoot() { return app.API + "Payment/Coupon/Verify"; }
    }
});