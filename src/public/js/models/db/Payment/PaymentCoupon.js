define([
    "models/db/Payment/Payment",
    "models/db/Payment/Coupon",
    
], function(Payment,
            Coupon){
    "use strict";

    return class PaymentCoupon extends app.BaseModel {

        defaults() {
            return {Couponid: null,
                    Paymentid: null,
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