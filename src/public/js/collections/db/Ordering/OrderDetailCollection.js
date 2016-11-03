define([
    "models/db/Ordering/OrderDetail"
], function(OrderDetail){
    "use strict";
    
    return class OrderDetailCollection extends app.BaseCollection
    {
        getModel() { return OrderDetail; }
        url() {return app.API + "DB/Ordering/OrderDetail";}
    }
});