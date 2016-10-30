define([
    "app"
], function(app){
    "use strict";

    return class OrderDetail extends Backbone.Model {
        
        idAttribute() { return 'OrderDetailid'; }

        defaults() {
            return {OrderDetailid: 0,
                    Orderid: 0,
                    Menuid: 0,
                    MenuSizeid: 0,
                    MenuGroupid: 0,
                    Amount: 0,
                    SinglePrice: 0,
                    SinglePriceModifiedByUserid: 0,
                    ExtraDetail: '',
                    Finished: null,
                    Availabilityid: 0,
                    AvailabilityAmount: 0,
                    Verified: false};
        }

    }
});