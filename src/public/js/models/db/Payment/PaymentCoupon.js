define([
    "models/db/Payment/Payment",
    "models/db/Payment/Coupon",
    "app"
], function(Payment,
            Coupon){
    "use strict";

    return class PaymentCoupon extends Backbone.Model {

        defaults() {
            return {Couponid: 0,
                    Paymentid: 0,
                    ValueUsed: 0};
        }
        
        parse(response)
        {
            if('Coupon' in response)
            {
                response.Coupon = new Coupon(response.Coupon, {parse: true});
            }
            
            if('Payment' in response)
            {
                response.Payment = new Payment(response.Payment, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});