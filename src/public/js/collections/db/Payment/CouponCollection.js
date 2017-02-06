define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class CouponCollection extends BaseCollection
    {
        getModel() { return app.models.Payment.Coupon; }
        url() {return app.API + "DB/Payment/Coupon";}
    }
});