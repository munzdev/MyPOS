define([
    "models/db/Ordering/OrderDetail"
], function(OrderDetail){
    "use strict";
    
    return class OrderDetailCollection extends Backbone.Collection
    {
        model() { return OrderDetail; }
        url() {return app.API + "DB/Ordering/OrderDetail";}
    }
});