define([
    "app",
    "collections/OrderCollection"
], function(app, OrderCollection){
    "use strict";

    var InfoModel = Backbone.Model.extend({
        url: app.API + 'Orders/GetOrderInfo/',
        defaults: function() {
            return {
                orderid: 0,
                table_name: '',
                ordertime: '',
                user: '',
                status: 0,
                open: 0,
                last_paydate: '',
                finished: '',
                amountPayed: 0,
                orders: new OrderCollection
            };
        },

        parse: function(response)
        {
            if(response.error)
            {
                MyPOS.DisplayError(response.errorMessage);
                return null;
    	    }
            else
            {
                var emulateResponse = {error: response.error,
                                       result: response.result.orders};
                response.result.orders = new OrderCollection(emulateResponse, {parse: true});
                return response.result;
            }
        }

    });

    return InfoModel;
});