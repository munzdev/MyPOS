define([
    "models/db/Ordering/OrderDetail"
], function(OrderDetail){
    "use strict";
    
    return class OrderModifyCollection extends app.BaseCollection
    {
        getModel() { return OrderDetail; }
        url() {return app.API + "Order/" + this.id;}
    }
});