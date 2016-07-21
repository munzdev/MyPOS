define(["app",
        "collections/order/payments/ExtraCollection",
        "collections/order/payments/OrderCollection"],
function(app, ExtraCollection, OrderCollection) {
    "use strict";

    var PaymentModel = Backbone.Model.extend({
        url: app.API + 'Orders/GetOpenPayments/',
        defaults: function() {
            return {
                orders: new OrderCollection,
                extras: new ExtraCollection
            }
        },

        parse: function(response)
        {
            response.orders = new OrderCollection(response.result.orders, {parse: true});
            response.extras = new ExtraCollection(response.result.extras, {parse: true});

            return response;
        }

    });

    return PaymentModel;
});