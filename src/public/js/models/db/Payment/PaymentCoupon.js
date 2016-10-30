define([
    "app"
], function(app){
    "use strict";

    return class PaymentCoupon extends Backbone.Model {

        defaults() {
            return {Couponid: 0,
                    Paymentid: 0,
                    ValueUsed: 0};
        }

    }
});