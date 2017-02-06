define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class PaymentCoupon extends BaseModel {

        defaults() {
            return {Couponid: null,
                    PaymentRecievedid: null,
                    ValueUsed: 0};
        }

        parse(response)
        {
            if('Coupon' in response)
            {
                response.Coupon = new app.models.Payment.Coupon(response.Coupon, {parse: true});
            }

            if('PaymentRecieved' in response)
            {
                response.PaymentRecieved = new app.models.Payment.PaymentRecieved(response.PaymentRecieved, {parse: true});
            }

            return super.parse(response);
        }
    }
});