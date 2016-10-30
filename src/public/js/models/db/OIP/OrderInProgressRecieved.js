define([
    "app"
], function(app){
    "use strict";

    return class OrderInProgressRecieved extends Backbone.Model {
        
        idAttribute() { return 'OrderInProgressRecievedid'; }

        defaults() {
            return {OrderInProgressRecievedid: 0,
                    OrderDetailid: 0,
                    OrderInProgressid: 0,
                    DistributionGivingOutid: 0,
                    Amount: 0};
        }

    }
});