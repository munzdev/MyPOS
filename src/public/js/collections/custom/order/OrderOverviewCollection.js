define([
    "models/db/Ordering/Order"
], function(Order){
    "use strict";

    return class OrderOverviewCollection extends app.BaseCollection
    {
        getModel() { return Order; }
        url() {return app.API + "Order";}
        parse(response) {
            this.count = response.Count;
            response = response.Order;
            return super.parse(response);
        }
    }
});