define([
    "models/db/Payment/PaymentRecieved",
    "models/db/Payment/Coupon",

], function(PaymentRecieved,
            Coupon){
    "use strict";

    return class PaymentCoupon extends app.BaseModel {

        defaults() {
            return {Couponid: null,
                    PaymentRecievedid: null,
                    ValueUsed: 0};
        }

        parse(response)
        {
            if('Coupon' in response)
            {
                response.Coupon = new Coupon(response.Coupon, {parse: true});
            }

            if('PaymentRecieved' in response)
            {
                response.PaymentRecieved = new PaymentRecieved(response.PaymentRecieved, {parse: true});
            }

            return super.parse(response);
        }
    }
});