define([
    "app"
], function(app){
    "use strict";

    return class Coupon extends Backbone.Model {
        
        idAttribute() { return 'Couponid'; }

        defaults() {
            return {Couponid: 0,
                    Eventid: 0,
                    CreatedBy: 0,
                    Code: '',
                    Created: null,
                    Value: 0};
        }

    }
});