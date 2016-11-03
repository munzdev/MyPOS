define([
    "models/custom/OrdersOverviewModel"
], function(OrdersOverviewModel){
    "use strict";

    var OrdersOverviewCollection = app.BaseCollection.extend({

        model: OrdersOverviewModel,
        url: app.API + "Orders/GetOpenList/",
        parse: function (response) {
            if(response.error)
            {
                MyPOS.DisplayError(response.errorMessage);
                return null;
            }
            else
            {
                return response.result;
            }
        }

    });

    return OrdersOverviewCollection;
});