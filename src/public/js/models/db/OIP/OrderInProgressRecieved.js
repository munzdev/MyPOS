define([
    "models/db/Ordering/OrderDetail",
    "models/db/OIP/OrderInProgress",
    "models/db/OIP/DistributionGivingOut",
    
], function(OrderDetail,
            OrderInProgress,
            DistributionGivingOut){
    "use strict";

    return class OrderInProgressRecieved extends app.BaseModel {
        
        idAttribute() { return 'OrderInProgressRecievedid'; }

        defaults() {
            return {OrderInProgressRecievedid: null,
                    OrderDetailid: null,
                    OrderInProgressid: null,
                    DistributionGivingOutid: null,
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