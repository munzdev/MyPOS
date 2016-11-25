define([
    "models/db/Event/EventTable",
    "models/db/User/User",
    "collections/db/OIP/OrderInProgressCollection",
    "collections/db/Ordering/OrderDetailCollection"
], function(EventTable,
            User,
            OrderInProgressCollection,
            OrderDetailCollection){
    "use strict";

    return class Order extends app.BaseModel {
        
        idAttribute() { return 'Orderid'; }

        defaults() {
            return {Orderid: null,
                    EventTableid: null,
                    Userid: null,
                    Ordertime: null,
                    Priority: 0,
                    Finished: null};
        }
        
        parse(response)
        {
            if('EventTable' in response)
            {
                response.EventTable = new EventTable(response.EventTable, {parse: true});
            }
            
            if('User' in response)
            {
                response.User = new User(response.User, {parse: true});
            }
            
            if('OrderInProgresses' in response)
            {   
                if(response.OrderInProgresses.toString() == '')
                    response.OrderInProgresses = new OrderInProgressCollection();
                else
                    response.OrderInProgresses = new OrderInProgressCollection(response.OrderInProgresses, {parse: true});
            }
            
            if('OrderDetails' in response)
            {                
                if(response.OrderDetails.toString() == '')
                    response.OrderDetails = new OrderDetailCollection();
                else
                    response.OrderDetails = new OrderDetailCollection(response.OrderDetails, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});