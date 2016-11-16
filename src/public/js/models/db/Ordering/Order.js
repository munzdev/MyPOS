define([
    "models/db/Event/EventTable",
    "models/db/User/User"
], function(EventTable,
            User){
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
                //-- use require for later module usage as on page load the dependencies are not correctly loaded
                require(["collections/db/OIP/OrderInProgressCollection"], (OrderInProgressCollection) => {
                    response.OrderInProgresses = new OrderInProgressCollection(response.OrderInProgresses, {parse: true});
                });                
            }
            
            return super.parse(response);
        }

    }
});