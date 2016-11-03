define([
    "models/order/payments/OrderModel"
], function(OrderModel){
    "use strict";

    var OrderCollection = app.BaseCollection.extend({
        model: OrderModel
    });

    return OrderCollection;
});