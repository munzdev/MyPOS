/**
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "collections/order/OrderCollection"
], function(OrderCollection){
    "use strict";

    var TypeModel = Backbone.Model.extend({

        defaults: function() {
            return {
                menu_typeid: 0,
                name: '',
                orders: new OrderCollection
            };
        },

        parse: function(response)
        {
            response.orders = new OrderCollection(response.orders, {parse: true});
            return response;
        }

    });

    return TypeModel;
});