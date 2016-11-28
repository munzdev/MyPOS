define([
    "models/db/Ordering/OrderDetail"
], function(OrderDetail){
    "use strict";
    
    return class OrderUnbilledCollection extends app.BaseCollection
    {
        initialize() {
            this.orderid = 0;
            this.all = false;
        }
        
        getModel() { return OrderDetail; }
        url() {return app.API + "Order/Unbilled/" + this.orderid + '/' + this.all;}
    }
});