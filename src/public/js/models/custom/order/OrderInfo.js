define([
    "models/db/Ordering/Order",
    "collections/db/Ordering/OrderDetailCollection"
], function(Order,
            OrderDetailCollection){
    "use strict";
    
    return class OrderInfo extends Order
    {
        defaults() {
            return _.extend(super.defaults(), {OrderDetails: new OrderDetailCollection()});
        }
        
        urlRoot() {
            return app.API + "Order/Info";
        }
    }
});