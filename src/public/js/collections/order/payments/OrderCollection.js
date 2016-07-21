define([
    "models/order/payments/OrderModel"
], function(OrderModel){
    "use strict";

    var OrderCollection = Backbone.Collection.extend({

        model: OrderModel
    });

    return OrderCollection;
});