define([
    "models/db/Ordering/Order",
    "collections/db/Ordering/OrderDetailCollection"
], function(Order,
            OrderDetailCollection){
    "use strict";
    
    return class OrderModify extends Order
    {
        defaults() {
            return _.extend(super.defaults(), {OrderDetail: new OrderDetailCollection()});
        }
        
        urlRoot() {
            return app.API + "Order/" + this.get(this.idAttribute());
        }
    }
});