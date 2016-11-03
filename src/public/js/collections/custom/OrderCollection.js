define([
    "models/order/TypeModel"
], function(TypeModel){
    "use strict";

    var OrderCollection = app.BaseCollection.extend({
    	model: TypeModel,
    	url: app.API + "Orders/GetOrder/",
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

    return OrderCollection;
});