define([
    "models/db/Ordering/Order"
], function(Order){
    "use strict";
    
    return class OrdersOverviewCollection extends app.BaseCollection
    {
        getModel() { return Order; }
        url() {return app.API + "Order";}
    }
});