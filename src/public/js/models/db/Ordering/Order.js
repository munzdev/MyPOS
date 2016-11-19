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

    return class Order extends Backbone.Model {
        
        idAttribute() { return 'Orderid'; }

        defaults() {
            return {Orderid: 0,
                    EventTableid: 0,
                    Userid: 0,
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
                response.OrderInProgresses = new OrderInProgressCollection(response.OrderInProgresses, {parse: true});
            }
            
            if('OrderDetail' in response)
            {                
                response.OrderDetail = new OrderDetailCollection(response.OrderDetail, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});