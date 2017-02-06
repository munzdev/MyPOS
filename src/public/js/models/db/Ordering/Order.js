define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class Order extends BaseModel {

        idAttribute() { return 'Orderid'; }

        defaults() {
            return {Orderid: null,
                    EventTableid: null,
                    Userid: null,
                    Ordertime: null,
                    Priority: 0,
                    DistributionFinished: null,
                    InvoiceFinished: null};
        }

        parse(response)
        {
            if('EventTable' in response)
            {
                response.EventTable = new app.models.Event.EventTable(response.EventTable, {parse: true});
            }

            if('UserRelatedByUserid' in response)
            {
                response.UserRelatedByUserid = new app.models.User.User(response.UserRelatedByUserid, {parse: true});
            }

            if('UserRelatedByCancellationCreatedByUserid' in response)
            {
                response.UserRelatedByCancellationCreatedByUserid = new app.models.User.User(response.UserRelatedByCancellationCreatedByUserid, {parse: true});
            }

            if('OrderInProgresses' in response)
            {
                if(response.OrderInProgresses.toString() == '')
                    response.OrderInProgresses = new app.collections.OIP.OrderInProgressCollection();
                else
                    response.OrderInProgresses = new app.collections.OIP.OrderInProgressCollection(response.OrderInProgresses, {parse: true});
            }

            if('OrderDetails' in response)
            {
                if(response.OrderDetails.toString() == '')
                    response.OrderDetails = new app.collections.Ordering.OrderDetailCollection();
                else
                    response.OrderDetails = new app.collections.Ordering.OrderDetailCollection(response.OrderDetails, {parse: true});
            }

            return super.parse(response);
        }

    }
});