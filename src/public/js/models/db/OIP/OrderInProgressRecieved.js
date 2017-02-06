define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class OrderInProgressRecieved extends BaseModel {

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
                response.OrderDetail = new app.models.Ordering.OrderDetail(response.OrderDetail, {parse: true});
            }

            if('OrderInProgress' in response)
            {
                response.OrderInProgress = new app.models.OIP.OrderInProgress(response.OrderInProgress, {parse: true});
            }

            if('DistributionGivingOut' in response)
            {
                response.DistributionGivingOut = new app.models.DistributionPlace.DistributionGivingOut(response.DistributionGivingOut, {parse: true});
            }

            return super.parse(response);
        }

    }
});