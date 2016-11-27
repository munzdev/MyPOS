define([
    "models/db/Ordering/Order",
    "collections/db/Ordering/OrderDetailCollection"
], function(Order,
            OrderDetailCollection){
    "use strict";
    
    return class OrderUnbilled extends Order
    {
        defaults() {
            return _.extend(super.defaults(), {All: false,
                                               OrderDetails: new OrderDetailCollection()});
        }
        
        url() {
            return app.API + 'Order/Unbilled/' + this.id + '/' + this.get('All');
        }
    }
});