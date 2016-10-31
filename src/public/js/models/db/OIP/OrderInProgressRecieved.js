define([
    "models/db/Ordering/OrderDetail",
    "models/db/OIP/OrderInProgress",
    "models/db/OIP/DistributionGivingOut",
    "app"
], function(OrderDetail,
            OrderInProgress,
            DistributionGivingOut){
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
        
        parse(response)
        {
            if('OrderDetail' in response)
            {
                response.OrderDetail = new OrderDetail(response.OrderDetail, {parse: true});
            }
            
            if('OrderInProgress' in response)
            {
                response.OrderInProgress = new OrderInProgress(response.OrderInProgress, {parse: true});
            }
            
            if('DistributionGivingOut' in response)
            {
                response.DistributionGivingOut = new DistributionGivingOut(response.DistributionGivingOut, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});